<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\authController;

Route::middleware('auth:scanctum')->get('/user', function (Request $request){
    return $request->user();
});

Route::post('registeruser', [AuthController::class, 'register']);