<?php

namespace App\Services\Property;

use App\Models\Property;
use App\Models\User;
use App\Services\Property\Exceptions\InvalidPropertyFieldValueException;

class CreatePropertyService
{
    public function __construct(
        private PropertyService $propertyService
    ) {
    }

    public function create(
        User $user,
        string $propertyType,
        ?array $features,
        ?float $price,
        ?float $taxes,
        ?float $income,
        ?float $expenditure,
    ): Property
    {
        if ($price !== null && $price < 0) {
            throw new InvalidPropertyFieldValueException('Price must be greater than or equal to 0');
        }

        return $this->propertyService->create([
            Property::USER_ID_COLUMN => $user->getId(),
            Property::PROPERTY_TYPE_COLUMN => $propertyType,
            Property::FEATURES_COLUMN => $features,
            Property::PRICE_COLUMN => $price,
            Property::TAXES_COLUMN => $taxes,
            Property::INCOME_COLUMN => $income,
            Property::EXPENDITURE_COLUMN => $expenditure,
        ]);
    }
}
