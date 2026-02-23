<?php

use App\Modules\CNAM\Controllers\CnamController;
use Illuminate\Support\Facades\Route;

Route::prefix('cnam')->name('cnam.')->group(function () {
    Route::get('/', [CnamController::class, 'index'])->name('index');
    Route::get('/create', [CnamController::class, 'create'])->name('create');
    Route::post('/', [CnamController::class, 'store'])->name('store');
    Route::post('/{id}/transmettre', [CnamController::class, 'transmettre'])->name('transmettre');
});
