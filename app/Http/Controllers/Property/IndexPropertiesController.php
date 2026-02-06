<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Services\Property\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IndexPropertiesController extends Controller
{
    public function __construct(
        private PropertyService $propertyService
    ) {
    }

    /**
     * List properties for the authenticated user (no pagination).
     */
    public function __invoke(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $user = $request->user();
            $properties = $this->propertyService->getByUserId($user->getId());

            return PropertyResource::collection($properties);
        } catch (\Throwable $e) {
            Log::error('failed to list properties', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to list properties.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
