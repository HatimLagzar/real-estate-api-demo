<?php

namespace App\Repositories;

use App\Models\Property;

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
     * Check if a property with the given hash already exists.
     */
    public function existsByHash(string $hash): bool
    {
        return Property::query()
            ->where(Property::HASH_COLUMN, $hash)
            ->exists();
    }
}
