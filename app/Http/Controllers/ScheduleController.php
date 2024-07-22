<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Paramedic;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function saveSchedule(Request $request)
    {
        $role = $request->input('role'); // Get the role from the request
        $staffId = $request->input('staff_id'); // Get the staff ID from the request

        // Log received data for debugging
        Log::info('Received Role: ' . $role);
        Log::info('Received Staff ID: ' . $staffId);

        // Validate that the role is either 'doctor' or 'paramedic'
        if (!in_array($role, ['doctor', 'paramedic'])) {
            Log::error('Invalid role: ' . $role); // Log the invalid role
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $scheduleData = $request->only([
            'monday_start', 'monday_end', 
            'tuesday_start', 'tuesday_end', 
            'wednesday_start', 'wednesday_end', 
            'thursday_start', 'thursday_end', 
            'friday_start', 'friday_end', 
            'saturday_start', 'saturday_end', 
            'sunday_start', 'sunday_end'
        ]);

        // Add the appropriate user ID column based on the role
        if ($role === 'doctor') {
            $scheduleData['doctor_id'] = $staffId;
        } elseif ($role === 'paramedic') {
            $scheduleData['paramedic_staff_id'] = $staffId;
        }

        $schedule = Schedule::updateOrCreate(
            [$role === 'doctor' ? 'doctor_id' : 'paramedic_staff_id' => $staffId],
            $scheduleData
        );

        return response()->json(['success' => true, 'schedule' => $schedule], 201);
    }

    public function getAllSchedules()
    {
        $schedules = Schedule::with('doctor', 'paramedicStaff')->get();
        return response()->json($schedules);
    }

    // public function getSchedule($id, $role)
    // {
    //     Log::info("getSchedule called with id: {$id}, role: {$role}");

    //     if ($role === 'doctor') {
    //         $schedule = Schedule::where('doctor_id', $id)->with('doctor')->first();
    //         Log::info("Querying doctor schedule with doctor_id: {$id}");
    //     } elseif ($role === 'paramedic') {
    //         $schedule = Schedule::where('paramedic_staff_id', $id)->with('paramedicStaff')->first();
    //         Log::info("Querying paramedic schedule with paramedic_staff_id: {$id}");
    //     } else {
    //         Log::error("Invalid role: {$role}");
    //         return response()->json(['error' => 'Invalid role'], 400);
    //     }

    //     if (!$schedule) {
    //         Log::error("Schedule not found for id: {$id}, role: {$role}");
    //         return response()->json(['error' => 'Schedule not found'], 404);
    //     }

    //     Log::info("Schedule found: ", ['schedule' => $schedule]);
    //     return response()->json($schedule);
    // }

    public function getLatestSchedule($id, $role)
    {
        Log::info("getLatestSchedule called with id: {$id}, role: {$role}");

        // Determine the column to search based on the role
        $column = $role === 'doctor' ? 'doctor_id' : 'paramedic_staff_id';

        // Fetch the latest schedule entry based on the column
        $schedule = Schedule::where($column, $id)->latest()->first();

        if (!$schedule) {
            Log::error("No schedule found for id: {$id}, role: {$role}");
            return response()->json(['error' => 'No schedule found'], 404);
        }

        Log::info("Latest schedule found: ", ['schedule' => $schedule]);
        return response()->json($schedule);
    }
}

