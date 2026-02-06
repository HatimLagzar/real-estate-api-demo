<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePropertyWebhookRequest;
use App\Models\Property;
use App\Models\User;
use App\Services\Property\CreatePropertyService;
use App\Services\Property\Exceptions\InvalidPropertyFieldValueException;
use App\Services\Property\PropertyService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreatePropertyWebhookController extends Controller
{
    public function __construct(
        private CreatePropertyService $createPropertyService,
        private PropertyService $propertyService,
        private UserService $userService
    ) {
    }

    /**
     * Create a property from webhook JSON payload.
     * Missing optional keys are stored as null in the database.
     */
    public function __invoke(CreatePropertyWebhookRequest $request): JsonResponse
    {
        $data = $this->normalizePayload($request->validated());

        $user = $this->userService->findById((int) $data[Property::USER_ID_COLUMN]);
        if (! $user instanceof User) {
            return response()->json([
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $propertyType = $data[Property::PROPERTY_TYPE_COLUMN];
        $priceForDedup = isset($data[Property::PRICE_COLUMN]) && $data[Property::PRICE_COLUMN] !== null
            ? (float) $data[Property::PRICE_COLUMN]
            : null;

        $hash = $this->propertyService->computeHash(
            $user->getId(),
            $propertyType,
            $data[Property::FEATURES_COLUMN],
            $priceForDedup
        );

        if ($this->propertyService->existsByHash($hash)) {
            return response()->json([
                'message' => 'A property with the same user, type, features and price already exists.',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $property = $this->createPropertyService->create(
                $user,
                $propertyType,
                $data[Property::FEATURES_COLUMN],
                $data[Property::PRICE_COLUMN] !== null ? (float) $data[Property::PRICE_COLUMN] : null,
                $data[Property::TAXES_COLUMN] !== null ? (float) $data[Property::TAXES_COLUMN] : null,
                $data[Property::INCOME_COLUMN] !== null ? (float) $data[Property::INCOME_COLUMN] : null,
                $data[Property::EXPENDITURE_COLUMN] !== null ? (float) $data[Property::EXPENDITURE_COLUMN] : null,
            );

            return response()->json([
                'message' => 'Property created.',
                'property' => $property,
            ], Response::HTTP_CREATED);
        } catch (InvalidPropertyFieldValueException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            Log::error('failed to create property from webhook', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to create property.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Ensure all property keys exist; missing ones become null.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizePayload(array $validated): array
    {
        $keys = [
            Property::USER_ID_COLUMN,
            Property::PROPERTY_TYPE_COLUMN,
            Property::FEATURES_COLUMN,
            Property::PRICE_COLUMN,
            Property::TAXES_COLUMN,
            Property::INCOME_COLUMN,
            Property::EXPENDITURE_COLUMN,
        ];

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = array_key_exists($key, $validated) ? $validated[$key] : null;
        }

        return $data;
    }
}
