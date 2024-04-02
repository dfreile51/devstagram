<?php

use App\Http\Controllers\ImagenController;
use Illuminate\Support\Facades\Route;


Route::post('/imagenes/{imagen}', [ImagenController::class, 'destroy']);
