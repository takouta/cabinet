<?php

use App\Modules\Stock\Controllers\StockMatierePremiereController;
use Illuminate\Support\Facades\Route;

Route::resource('stock', StockMatierePremiereController::class);