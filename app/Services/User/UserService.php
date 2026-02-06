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

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $data  Keys matching User fillable columns.
     * @return User
     */
    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
