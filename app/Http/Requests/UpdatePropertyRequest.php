<?php

namespace App\Http\Requests;

use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            Property::USER_ID_COLUMN => ['sometimes', 'integer', 'exists:users,id'],
            Property::PROPERTY_TYPE_COLUMN => ['sometimes', 'string', 'in:residential,commercial,land'],
            Property::FEATURES_COLUMN => ['sometimes', 'nullable', 'array'],
            Property::FEATURES_COLUMN . '.*' => ['string'],
            Property::PRICE_COLUMN => ['sometimes', 'nullable', 'numeric', 'min:0'],
            Property::TAXES_COLUMN => ['sometimes', 'nullable', 'numeric', 'min:0'],
            Property::INCOME_COLUMN => ['sometimes', 'nullable', 'numeric', 'min:0'],
            Property::EXPENDITURE_COLUMN => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ];
    }
}
