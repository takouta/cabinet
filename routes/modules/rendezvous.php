<?php

use App\Modules\RendezVous\Controllers\RendezVousController;
use Illuminate\Support\Facades\Route;

Route::resource('rendezvous', RendezVousController::class);