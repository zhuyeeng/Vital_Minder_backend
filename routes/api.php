<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\fetchStaffController;
use App\Http\Controllers\updateController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use App\Mail\MailNotify;
use App\Http\Controllers\WaitingListController;

// Authentication routes
Route::post('/registeruser', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Admin functions
Route::get('/fetchStaff', [fetchStaffController::class, 'getAllMedicalStaff']);
Route::get('/fetchPatient', [fetchStaffController::class, 'getAllPatient']);
Route::post('/ban-user', [updateController::class, 'banUser']);
Route::post('/unban-user', [updateController::class, 'unbanUser']);
Route::put('/update-staff/{id}', [UpdateController::class, 'updateStaff']);

// Securing patient functions and appointments with authentication
Route::middleware('auth:sanctum')->group(function () {
    // Reminder functions
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
    Route::get('/appointments', [AppointmentController::class, 'getPendingAppointments']);
    Route::put('/appointments/status/{id}', [AppointmentController::class, 'updateStatus']);
    Route::get('/appointments/pending-and-accepted', [AppointmentController::class, 'getPendingAndAcceptedAppointments']); // New route
});

// Testing the fetch function for fetching the accepted and pending appointment (Work)
Route::get('/appointmentPendingAccepted', [AppointmentController::class, 'getPendingAndAcceptedAppointments']);

//paramedic staff function
// Waiting list functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/appointments/accepted', [AppointmentController::class, 'getAcceptedAppointments']);
    Route::get('/waiting-list', [WaitingListController::class, 'getWaitingList']);
});

Route::middleware('auth:sanctum')->post('/waiting-list', [WaitingListController::class, 'addToWaitingList']);

// New route to get all medical staff with details
Route::get('/fetchMedicalStaffWithDetails', [fetchStaffController::class, 'getAllMedicalStaffWithDetails']);

// New route to get paramedic ID by user ID
Route::get('/paramedic-id/{userId}', [fetchStaffController::class, 'getParamedicIdByUserId']);

Route::post('/test', function () {
    Mail::to('zhuyeeng0524@gmail.com')->send(new MailNotify()); //email should put in the controller
    return response()->json(['message' => 'Email sent successfully.']);
});
