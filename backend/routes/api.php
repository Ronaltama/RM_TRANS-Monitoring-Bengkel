<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authentikasi\AuthController;
use App\Http\Controllers\Api\Armada\ArmadaController;
use App\Http\Controllers\Api\Armada\JenisArmadaController;

// Login
Route::post('/login', [AuthController::class, 'login']);

// midleware auth
Route::middleware('auth:sanctum')->group(function () {
    // Armada
    Route::apiResource('armada', ArmadaController::class);
    // Jenis Armada
    Route::apiResource('jenis_armada', JenisArmadaController::class);
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
