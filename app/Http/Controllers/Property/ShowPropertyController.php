<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ShowPropertyController extends Controller
{
    /**
     * Show a single property. Property is resolved by route model binding.
     */
    public function __invoke(Property $property): PropertyResource|JsonResponse
    {
        try {
            return new PropertyResource($property);
        } catch (\Throwable $e) {
            Log::error('failed to show property', [
                'property_id' => $property->getId(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to show property.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
