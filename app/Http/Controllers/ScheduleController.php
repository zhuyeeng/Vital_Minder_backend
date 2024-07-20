<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\ParamedicStaff;

class ScheduleController extends Controller
{
    public function setSchedule(Request $request, $staffId, $type)
    {
        $validated = $request->validate([
            'monday_start' => 'nullable|date_format:H:i',
            'monday_end' => 'nullable|date_format:H:i',
            'tuesday_start' => 'nullable|date_format:H:i',
            'tuesday_end' => 'nullable|date_format:H:i',
            'wednesday_start' => 'nullable|date_format:H:i',
            'wednesday_end' => 'nullable|date_format:H:i',
            'thursday_start' => 'nullable|date_format:H:i',
            'thursday_end' => 'nullable|date_format:H:i',
            'friday_start' => 'nullable|date_format:H:i',
            'friday_end' => 'nullable|date_format:H:i',
            'saturday_start' => 'nullable|date_format:H:i',
            'saturday_end' => 'nullable|date_format:H:i',
            'sunday_start' => 'nullable|date_format:H:i',
            'sunday_end' => 'nullable|date_format:H:i',
        ]);

        $schedule = Schedule::updateOrCreate(
            [$type . '_id' => $staffId],
            $validated
        );

        return response()->json($schedule);
    }

    public function getSchedule($staffId, $type)
    {
        $schedule = Schedule::where($type . '_id', $staffId)->firstOrFail();
        $staffInfo = ($type === 'doctor') ? Doctor::findOrFail($staffId) : ParamedicStaff::findOrFail($staffId);

        return response()->json([
            'schedule' => $schedule,
            'staffInfo' => $staffInfo,
        ]);
    }

    public function getAllSchedules()
    {
        $schedules = Schedule::with('doctor', 'paramedicStaff')->get();
        return response()->json($schedules);
    }
}
