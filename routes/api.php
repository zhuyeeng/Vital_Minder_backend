<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\fetchStaffController;
use App\Http\Controllers\updateController;

Route::post('/registeruser', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/fetchStaff', [fetchStaffController::class, 'getAllMedicalStaff']);
Route::post('/ban-user', [updateController::class, 'banUser']);
Route::post('/unban-user', [updateController::class, 'unbanUser']);
Route::put('/update-staff/{id}', [UpdateController::class, 'updateStaff']);