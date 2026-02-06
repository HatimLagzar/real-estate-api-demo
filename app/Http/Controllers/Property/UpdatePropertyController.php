<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Services\Property\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdatePropertyController extends Controller
{
    public function __construct(
        private PropertyService $propertyService
    ) {
    }

    /**
     * Update a property. Property is resolved by route model binding.
     */
    public function __invoke(UpdatePropertyRequest $request, Property $property): PropertyResource|JsonResponse
    {
        try {
            $property = $this->propertyService->update($property, $request->validated());

            return new PropertyResource($property);
        } catch (\Throwable $e) {
            Log::error('failed to update property', [
                'property_id' => $property->getId(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to update property.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
