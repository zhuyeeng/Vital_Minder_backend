<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\fetchStaffController;
use App\Http\Controllers\updateController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;

Route::post('/registeruser', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/password-reset-request', [AuthController::class, 'sendPasswordResetEmail']);

// Admin functions
Route::get('/fetchStaff', [fetchStaffController::class, 'getAllMedicalStaff']);
Route::get('/fetchPatient', [fetchStaffController::class, 'getAllPatient']);
Route::post('/ban-user', [updateController::class, 'banUser']);
Route::post('/unban-user', [updateController::class, 'unbanUser']);
Route::put('/update-staff/{id}', [UpdateController::class, 'updateStaff']);

// Patient functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fetch-reminder', [ReminderController::class, 'index']);
    Route::post('/add-reminder', [ReminderController::class, 'PatientStore']);
    Route::get('/fetch-reminder/{id}', [ReminderController::class, 'show']);
    Route::put('/update-reminder/{id}', [ReminderController::class, 'update']);
    Route::delete('/delete-reminder/{id}', [ReminderController::class, 'destroy']);

    // Appointment functions
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/creator/{userId}', [AppointmentController::class, 'showByCreatorId']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::get('/appointments/user/{userId}', [AppointmentController::class, 'showByUserId']);
    Route::get('/appointments/patient-id/{userId}', [AppointmentController::class, 'getPatientIdByUserId']);
});
