<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            User::EMAIL_COLUMN => ['required', 'string', 'email'],
            User::PASSWORD_COLUMN => ['required', 'string'],
        ];
    }
}
