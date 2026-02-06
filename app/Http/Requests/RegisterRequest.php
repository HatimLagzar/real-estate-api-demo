<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            User::NAME_COLUMN => ['required', 'string', 'max:255'],
            User::EMAIL_COLUMN => ['required', 'string', 'email', 'max:255',],
            User::PASSWORD_COLUMN => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
