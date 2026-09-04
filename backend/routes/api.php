<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\JenisPerangkatController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\PerangkatController;
use App\Http\Controllers\Api\Sesi_rentalController;


route::post('/register', [AuthController::class, 'register']);
route::post('/login', [AuthController::class, 'login']);
route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::post('/pelanggan', [PelangganController::class, 'store']);
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

    Route::get('/jenis_perangkat', [JenisPerangkatController::class, 'index']);
    Route::post('/jenis_perangkat', [JenisPerangkatController::class, 'store']);
    Route::put('/jenis_perangkat/{id}', [JenisPerangkatController::class, 'update']);
    Route::delete('/jenis_perangkat/{id}', [JenisPerangkatController::class, 'destroy']);

    Route::get('/perangkat', [PerangkatController::class, 'index']);
    Route::post('/perangkat', [PerangkatController::class, 'store']);
    Route::get('/perangkat/{id}', [PerangkatController::class, 'show']);
    Route::put('/perangkat/{id}', [PerangkatController::class, 'update']);
    Route::delete('/perangkat/{id}', [PerangkatController::class, 'destroy']);

    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::post('/pembayaran', [PembayaranController::class, 'store']);
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show']);
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update']);
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy']);

    Route::get('/sesi_rental', [Sesi_rentalController::class, 'index']);
    Route::post('/sesi_rental', [Sesi_rentalController::class, 'store']);
    Route::get('/sesi_rental/{id}', [Sesi_rentalController::class, 'show']);
    Route::put('/sesi_rental/{id}', [Sesi_rentalController::class, 'update']);
    Route::delete('/sesi_rental/{id}', [Sesi_rentalController::class, 'destroy']);
});