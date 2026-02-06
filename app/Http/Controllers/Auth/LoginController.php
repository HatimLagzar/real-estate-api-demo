<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    /**
     * Authenticate user and return a Sanctum token.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            if (! Auth::attempt($request->only(User::EMAIL_COLUMN, User::PASSWORD_COLUMN))) {
                return response()->json([
                    'message' => 'Invalid credentials.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'message' => 'Authenticated.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('login failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Login failed.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
