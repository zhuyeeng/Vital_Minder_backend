<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\authController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/registeruser', [authController::class, 'register']);


// For testing the Router or API work or not.
// Route::get('/test', function () {
//     return response()->json(['message' => 'API is working']);
// });