<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * Create a new user (no token).
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($this->userService->findByEmail($data[User::EMAIL_COLUMN]) !== null) {
                return response()->json([
                    'message' => 'The email has already been taken.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = $this->userService->create([
                User::NAME_COLUMN => $data[User::NAME_COLUMN],
                User::EMAIL_COLUMN => $data[User::EMAIL_COLUMN],
                User::PASSWORD_COLUMN => $data[User::PASSWORD_COLUMN],
            ]);

            return response()->json([
                'message' => 'User registered.',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getAttribute(User::NAME_COLUMN),
                    'email' => $user->getAttribute(User::EMAIL_COLUMN),
                ],
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            Log::error('registration failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Registration failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
