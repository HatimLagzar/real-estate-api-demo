<?php

namespace App\Http\Resources;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property_type' => $this->getAttribute(Property::PROPERTY_TYPE_COLUMN),
            'features' => $this->getAttribute(Property::FEATURES_COLUMN),
            'price' => $this->getAttribute(Property::PRICE_COLUMN),
            'taxes' => $this->getAttribute(Property::TAXES_COLUMN),
            'income' => $this->getAttribute(Property::INCOME_COLUMN),
            'expenditure' => $this->getAttribute(Property::EXPENDITURE_COLUMN),
            'user_id' => $this->getAttribute(Property::USER_ID_COLUMN),
            'created_at' => $this->getAttribute(Property::CREATED_AT_COLUMN)?->toIso8601String(),
            'updated_at' => $this->getAttribute(Property::UPDATED_AT_COLUMN)?->toIso8601String(),
        ];
    }
}
