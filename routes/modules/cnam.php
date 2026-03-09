<?php

use App\Modules\CNAM\Controllers\CnamController;
use Illuminate\Support\Facades\Route;

Route::prefix('cnam')->name('cnam.')->group(function () {
    Route::get('/', [CnamController::class, 'index'])->name('index');
    Route::get('/create', [CnamController::class, 'create'])->name('create');
    Route::post('/', [CnamController::class, 'store'])->name('store');
    Route::post('/{id}/transmettre', [CnamController::class, 'transmettre'])->name('transmettre');
    Route::get('/{id}/pdf', [CnamController::class, 'generatePdf'])->name('pdf');
    Route::get('/export/daily-pdf', [CnamController::class, 'generateDailyPdf'])->name('daily-pdf');

    // Saisie des soins (BS)
    Route::get('/soins/create', [CnamController::class, 'createSoin'])->name('soins.create');
    Route::post('/soins', [CnamController::class, 'storeSoin'])->name('soins.store');
});
