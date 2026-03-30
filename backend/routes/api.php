<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Login
Route::post('/login', [AuthController::class, 'login']);

// Logout (butuh token)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
