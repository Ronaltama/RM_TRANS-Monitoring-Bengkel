<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authentikasi\AuthController;
use App\Http\Controllers\Api\Armada\ArmadaController;
use App\Http\Controllers\Api\Armada\JenisArmadaController;
use App\Http\Controllers\Api\KategoriKomponen\KategoriKomponenController;

// Login
Route::post('/login', [AuthController::class, 'login']);

// midleware auth
Route::middleware('auth:sanctum')->group(function () {
    // Armada
    Route::apiResource('armada', ArmadaController::class);
    // Jenis Armada
    Route::apiResource('jenis_armada', JenisArmadaController::class);
    // Category Componen (hanya Get all, create, delete)
    Route::get('kategori_komponen', [KategoriKomponenController::class, 'index']);
    Route::post('kategori_komponen', [KategoriKomponenController::class, 'store']);
    Route::delete('kategori_komponen/{id}', [KategoriKomponenController::class, 'destroy']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
