<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\fetchStaffController;
use App\Http\Controllers\updateController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaitingListController;
use App\Http\Controllers\patientReportController;
use App\Http\Controllers\MedicationReportController;
use App\Mail\MailNotify;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ScheduleController;

// Authentication routes
Route::post('/registeruser', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
});

// Admin functions
Route::get('/fetchStaff', [fetchStaffController::class, 'getAllMedicalStaff']);
Route::post('/ban-user', [updateController::class, 'banUser']);
Route::post('/unban-user', [updateController::class, 'unbanUser']);
Route::put('/update-staff/{id}', [updateController::class, 'updateStaff']);
Route::get('/fetchMedicalStaffWithDetails', [fetchStaffController::class, 'getAllMedicalStaffWithDetails']);
Route::get('/staff/{userId}', [fetchStaffController::class, 'getStaffByUserId']);

// Reminder functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fetch-reminder', [ReminderController::class, 'index']);
    Route::post('/add-reminder', [ReminderController::class, 'PatientStore']);
    Route::get('/fetch-reminder/{id}', [ReminderController::class, 'show']);
    Route::put('/update-reminder/{id}', [ReminderController::class, 'update']);
    Route::delete('/delete-reminder/{id}', [ReminderController::class, 'destroy']);
});

// Appointment functions
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/creator/{userId}', [AppointmentController::class, 'showByCreatorId']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::get('/appointments/user/{userId}', [AppointmentController::class, 'showByUserId']);
    Route::get('/appointments/patient-id/{userId}', [AppointmentController::class, 'getPatientIdByUserId']);
    Route::get('/appointments', [AppointmentController::class, 'getPendingAppointments']);
    Route::put('/appointments/status/{id}', [AppointmentController::class, 'updateStatus']);
    Route::get('/appointments/pending-and-accepted', [AppointmentController::class, 'getPendingAndAcceptedAppointments']);
    Route::get('/appointments-summary', [AppointmentController::class, 'getAppointmentsSummary']);
    Route::get('/appointments/accepted', [AppointmentController::class, 'getAcceptedAppointments']);
    Route::get('/appointments/doctor/{doctorId}', [AppointmentController::class, 'getAppointmentsByDoctorId']);
    Route::get('/doctor-id/{userId}', [AppointmentController::class, 'getDoctorIdByUserId']);
});

// Waiting list functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/waiting-list', [WaitingListController::class, 'getWaitingList']);
    Route::post('/waiting-list', [WaitingListController::class, 'addToWaitingList']);
    Route::get('/waiting-list/doctor', [WaitingListController::class, 'getDoctorWaitingList']); // New route
});

// Patient Report functions
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/patient-reports', [patientReportController::class, 'storePatientReport']);
    Route::post('/save-diagnosis-note', [MedicationReportController::class, 'store']);
    Route::get('/patients', [fetchStaffController::class, 'getAllPatients']);
});

// General routes
Route::get('/paramedic-id/{userId}', [fetchStaffController::class, 'getParamedicIdByUserId']);
Route::get('/appointmentPendingAccepted', [AppointmentController::class, 'getPendingAndAcceptedAppointments']);

// Chatbot and Scheduling routes
Route::post('/chat', [ChatController::class, 'chat']);

Route::get('/patients/search/{username}', [fetchStaffController::class, 'searchPatientsByUsername']);
Route::get('/patient-reports/{patientId}', [PatientReportController::class, 'getReportsByPatientId']);
Route::get('/medication-reports/{patientId}', [MedicationReportController::class, 'getMedicationReportsByPatientId']);

// Medication Report functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/medication-reports', [MedicationReportController::class, 'index']);
    Route::get('/medication-reports/{medicationReport}', [MedicationReportController::class, 'showMedicationReport']);
    Route::post('/medication-reports', [MedicationReportController::class, 'storeMedicationReport']);
    Route::patch('/medication-reports/{medicationReport}/status', [MedicationReportController::class, 'updateReportStatus']);
});

Route::post('/doctors/{id}/schedule', [ScheduleController::class, 'setSchedule']);
Route::get('/doctors/{id}/schedule', [ScheduleController::class, 'getSchedule']);
Route::post('/paramedic_staff/{id}/schedule', [ScheduleController::class, 'setSchedule']);
Route::get('/paramedic_staff/{id}/schedule', [ScheduleController::class, 'getSchedule']);
Route::get('/schedules', [ScheduleController::class, 'getAllSchedules']);

Route::get('/patient-id/{userId}', [fetchStaffController::class, 'getPatientIdByUserId']);

// routes/web.php or routes/api.php
Route::get('/doctors', [fetchStaffController::class, 'getAllDoctors']);
