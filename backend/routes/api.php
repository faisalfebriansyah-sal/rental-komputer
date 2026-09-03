<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


route::post('/register', [AuthController::class, 'register']);
route::post('/login', [AuthController::class, 'login']);
route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
