<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignatureCheckPropertyCreationWebhookMiddleware
{
    /**
     * Handle an incoming request. Expects a static token in the X-Webhook-Token header.
     */
    public function handle(Request $request, Closure $next, string $configKey = 'services.webhook.property_token'): Response
    {
        $expectedToken = config($configKey);

        if (empty($expectedToken)) {
            return response()->json([
                'message' => 'Webhook is not configured.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $token = $request->header('X-Webhook-Token');

        if ($token === null || ! hash_equals($expectedToken, $token)) {
            return response()->json([
                'message' => 'Invalid or missing webhook token.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
