<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Models\User;
use App\Services\Property\CreatePropertyService;
use App\Services\Property\Exceptions\InvalidPropertyFieldValueException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StorePropertyController extends Controller
{
    public function __construct(
        private CreatePropertyService $createPropertyService,
    ) {
    }

    /**
     * Create a new property.
     */
    public function __invoke(StorePropertyRequest $request): PropertyResource|JsonResponse
    {
        try {
            $data = $request->validated();
            
            /** @var User $user */
            $user = $request->user();

            $property = $this->createPropertyService->create(
                $user,
                $data[Property::PROPERTY_TYPE_COLUMN],
                $data[Property::FEATURES_COLUMN] ?? null,
                isset($data[Property::PRICE_COLUMN]) ? (float) $data[Property::PRICE_COLUMN] : null,
                isset($data[Property::TAXES_COLUMN]) ? (float) $data[Property::TAXES_COLUMN] : null,
                isset($data[Property::INCOME_COLUMN]) ? (float) $data[Property::INCOME_COLUMN] : null,
                isset($data[Property::EXPENDITURE_COLUMN]) ? (float) $data[Property::EXPENDITURE_COLUMN] : null,
            );

            return (new PropertyResource($property))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (InvalidPropertyFieldValueException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            Log::error('failed to create property', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to create property.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
