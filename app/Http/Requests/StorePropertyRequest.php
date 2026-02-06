<?php

namespace App\Http\Requests;

use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
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
            Property::USER_ID_COLUMN => ['required', 'integer', 'exists:users,id'],
            Property::PROPERTY_TYPE_COLUMN => ['required', 'string', 'in:residential,commercial,land'],
            Property::FEATURES_COLUMN => ['nullable', 'array'],
            Property::FEATURES_COLUMN . '.*' => ['string'],
            Property::PRICE_COLUMN => ['nullable', 'numeric', 'min:0'],
            Property::TAXES_COLUMN => ['nullable', 'numeric', 'min:0'],
            Property::INCOME_COLUMN => ['nullable', 'numeric', 'min:0'],
            Property::EXPENDITURE_COLUMN => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
