<?php

namespace App\Services\Property;

use App\Models\Property;
use App\Repositories\PropertyRepository;

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
        $hash = $this->computeHash($data[Property::USER_ID_COLUMN], $data[Property::PROPERTY_TYPE_COLUMN], $data[Property::FEATURES_COLUMN], $data[Property::PRICE_COLUMN]);
        $data[Property::HASH_COLUMN] = $hash;

        return $this->propertyRepository->create($data);
    }
}
