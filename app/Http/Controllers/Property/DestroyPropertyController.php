<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\Property\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DestroyPropertyController extends Controller
{
    public function __construct(
        private PropertyService $propertyService
    ) {
    }

    /**
     * Delete a property. Property is resolved by route model binding.
     */
    public function __invoke(Property $property): Response|JsonResponse
    {
        try {
            $this->propertyService->delete($property);

            return response()->noContent();
        } catch (\Throwable $e) {
            Log::error('failed to delete property', [
                'property_id' => $property->getId(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to delete property.',
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
