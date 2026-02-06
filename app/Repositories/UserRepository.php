<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Find a user by id.
     */
    public function findById(int $id): ?User
    {
        return User::query()
            ->where(User::ID_COLUMN, $id)
            ->first();
    }

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where(User::EMAIL_COLUMN, $email)
            ->first();
    }

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $data  Keys matching User fillable columns.
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }
}
