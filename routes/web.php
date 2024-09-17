<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;


Route::get('/{any}', [FrontController::class, 'index'])->where('any', '.*');
