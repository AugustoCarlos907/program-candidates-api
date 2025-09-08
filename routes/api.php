<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\ProgramaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::middleware( 'throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('candidatos', CandidatosController::class);
    Route::apiResource('candidaturas', CandidaturaController::class);
    Route::apiResource('programas', ProgramaController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
