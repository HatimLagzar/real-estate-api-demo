<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Find a user by id.
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
