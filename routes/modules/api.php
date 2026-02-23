<?php

use App\Modules\Auth\Controllers\LoginController;
use App\Modules\RendezVous\Controllers\API\RendezVousAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->name('api.')->middleware('api')->group(function () {
    Route::post('/login', [LoginController::class, 'apiLogin'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user');

        Route::post('/logout', [LoginController::class, 'apiLogout'])->name('logout');
    });

    Route::apiResource('rendezvous', RendezVousAPIController::class);
});
