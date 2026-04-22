<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('clients',      ClientController::class)->except(['show', 'create', 'edit']);
    Route::apiResource('services',     ServiceController::class)->only(['index', 'store']);
    Route::apiResource('appointments', AppointmentController::class)->only(['index', 'store', 'update']);
});
