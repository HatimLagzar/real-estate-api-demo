<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepository
{
    /**
     * Create a new property in the database.
     *
     * @param  array<string, mixed>  $data  Keys matching Property fillable columns.
     * @return Property
     */
    public function create(array $data): Property
    {
        return Property::create($data);
    }

    /**
     * Find a property by id.
     */
    public function findById(int $id): ?Property
    {
        return Property::query()
            ->where(Property::ID_COLUMN, $id)
            ->first();
    }

    /**
     * Get all properties for a user (no pagination).
     *
     * @return Collection<int, Property>
     */
    public function getByUserId(int $userId): Collection
    {
        return Property::query()
            ->where(Property::USER_ID_COLUMN, $userId)
            ->orderBy(Property::CREATED_AT_COLUMN, 'desc')
            ->get();
    }

    /**
     * Paginate properties.
     *
     * @return LengthAwarePaginator<Property>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Property::query()
            ->orderBy(Property::CREATED_AT_COLUMN, 'desc')
            ->paginate($perPage);
    }

    /**
     * Update a property.
     *
     * @param  array<string, mixed>  $data
     * @return Property
     */
    public function update(Property $property, array $data): Property
    {
        $property->update($data);

        return $property->fresh();
    }

    /**
     * Delete a property.
     */
    public function delete(Property $property): bool
    {
        return $property->delete();
    }

    /**
     * Check if a property with the given hash already exists.
     */
    public function existsByHash(string $hash): bool
    {
        return Property::query()
            ->where(Property::HASH_COLUMN, $hash)
            ->exists();
    }
}
