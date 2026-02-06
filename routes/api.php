<?php

use App\Http\Controllers\Webhook\CreatePropertyWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/properties', CreatePropertyWebhookController::class)
    ->middleware('webhook.token');
