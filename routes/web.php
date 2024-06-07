<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\authController;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/register', [authController::class, 'register']);

