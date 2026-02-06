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
}
