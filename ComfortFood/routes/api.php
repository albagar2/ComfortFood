<?php

use App\Http\Controllers\Api\RestauranteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes
Route::get('/restaurantes', [RestauranteController::class, 'index']);
Route::get('/restaurantes/{id}', [RestauranteController::class, 'show']);
