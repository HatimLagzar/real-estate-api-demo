<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Property\DestroyPropertyController;
use App\Http\Controllers\Property\IndexPropertiesController;
use App\Http\Controllers\Property\ShowPropertyController;
use App\Http\Controllers\Property\StorePropertyController;
use App\Http\Controllers\Property\UpdatePropertyController;
use App\Http\Controllers\Webhook\CreatePropertyWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('login');
Route::post('/register', RegisterController::class)->name('register');

Route::post('/webhooks/properties', CreatePropertyWebhookController::class)
    ->middleware('webhook.token');

Route::get('/properties', IndexPropertiesController::class)->name('properties.index')->middleware('auth:sanctum');
Route::get('/properties/{property}', ShowPropertyController::class)->name('properties.show');
Route::post('/properties', StorePropertyController::class)->name('properties.store');
Route::match(['put', 'patch'], '/properties/{property}', UpdatePropertyController::class)->name('properties.update');
Route::delete('/properties/{property}', DestroyPropertyController::class)->name('properties.destroy');
