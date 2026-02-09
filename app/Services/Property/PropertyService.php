<?php

namespace App\Services\Property;

use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PropertyService
{
    public function __construct(
        private PropertyRepository $propertyRepository
    ) {
    }

    /**
     * Compute a deterministic hash from user_id, property_type, features and price.
     * Reusable for deduplication checks and when storing on the property.
     */
    public function computeHash(
        int $userId,
        string $propertyType,
        ?array $features,
        ?float $price
    ): string {
        $featuresNormalized = $features ?? [];
        if (is_array($featuresNormalized)) {
            $featuresNormalized = array_map(fn (string $f): string => strtolower(trim($f)), $featuresNormalized);
            sort($featuresNormalized);
        }
        $priceNormalized = $price === null ? 'null' : (string) round($price, 2);
        $payload = $userId . '|' . $propertyType . '|' . json_encode($featuresNormalized) . '|' . $priceNormalized;

        return hash('sha256', $payload);
    }

    /**
     * Check if a property with the given hash already exists.
     */
    public function existsByHash(string $hash): bool
    {
        return $this->propertyRepository->existsByHash($hash);
    }

    /**
     * Create a new property in the database.
     *
     * @param  array<string, mixed>  $data  Keys matching Property fillable columns (including hash).
     * @return Property
     */
    public function create(array $data): Property
    {
        $hash = $this->computeHash(
            (int) $data[Property::USER_ID_COLUMN],
            (string) $data[Property::PROPERTY_TYPE_COLUMN],
            $data[Property::FEATURES_COLUMN] ?? null,
            isset($data[Property::PRICE_COLUMN]) ? (float) $data[Property::PRICE_COLUMN] : null
        );
        $data[Property::HASH_COLUMN] = $hash;

        return $this->propertyRepository->create($data);
    }

    /**
     * Find a property by id.
     */
    public function findById(int $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }

    /**
     * Get all properties for a user (no pagination).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Property>
     */
    public function getByUserId(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->propertyRepository->getByUserId($userId);
    }

    /**
     * Paginate properties.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Property>
     */
    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->propertyRepository->paginate($perPage);
    }

    /**
     * Update a property. user_id is never updated. Recalculates hash if property_type, features or price change.
     *
     * @param  array<string, mixed>  $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        unset($data[Property::USER_ID_COLUMN]);

        $hashKeys = [
            Property::PROPERTY_TYPE_COLUMN,
            Property::FEATURES_COLUMN,
            Property::PRICE_COLUMN,
        ];
        $affectsHash = count(array_intersect_key($data, array_flip($hashKeys))) > 0;
        if ($affectsHash) {
            $userId = (int) $property->getAttribute(Property::USER_ID_COLUMN);
            $type = (string) ($data[Property::PROPERTY_TYPE_COLUMN] ?? $property->getAttribute(Property::PROPERTY_TYPE_COLUMN));
            $features = $data[Property::FEATURES_COLUMN] ?? $property->getAttribute(Property::FEATURES_COLUMN);
            $price = isset($data[Property::PRICE_COLUMN]) ? (float) $data[Property::PRICE_COLUMN] : ($property->getAttribute(Property::PRICE_COLUMN) !== null ? (float) $property->getAttribute(Property::PRICE_COLUMN) : null);
            $data[Property::HASH_COLUMN] = $this->computeHash($userId, $type, $features, $price);
        }

        return $this->propertyRepository->update($property, $data);
    }

    /**
     * Delete a property.
     */
    public function delete(Property $property): bool
    {
        return $this->propertyRepository->delete($property);
    }
}
