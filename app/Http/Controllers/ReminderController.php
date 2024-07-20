<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    // Get all reminders for the authenticated user
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Join the users and patients tables and fetch the reminders
        $reminders = Reminder::with(['user', 'patient'])
            ->whereHas('patient', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        // Append the username of the creator (from users table) to each reminder
        foreach ($reminders as $reminder) {
            $reminder->creator_username = User::find($reminder->created_by)->username;
        }

        return response()->json($reminders);
    }

    public function PatientStore(Request $request)
    {
        $validatedData = $request->validate([
            'created_by' => 'required|exists:users,id',
            'reminder_name' => 'required|string|max:255',
            'medication_types' => 'required|string|max:255',
            'pills_number' => 'required|string|max:255',
            'time' => 'required',
            'frequency' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'side_effects' => 'nullable|string',
        ]);

        $patient = Patient::where('user_id', $request->created_by)->first();

        if ($patient) {
            $validatedData['patient_id'] = $patient->id;
        } else {
            return response()->json(['error' => 'No patient found for the user'], 400);
        }

        $reminder = Reminder::create($validatedData);

        return response()->json($reminder, 201);
    }

    // Get a specific reminder by its ID
    public function show($id)
    {
        $reminder = Reminder::with(['user', 'patient'])->findOrFail($id);
        return response()->json($reminder);
    }

    // Update a specific reminder
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'reminder_name' => 'required|string|max:255',
            'medication_types' => 'required|string|max:255',
            'pills_number' => 'required|string|max:255',
            'time' => 'required',
            'frequency' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'side_effects' => 'nullable|string',
        ]);

        $reminder = Reminder::findOrFail($id);
        $reminder->update($validatedData);

        return response()->json($reminder);
    }

    // Delete a specific reminder
    public function destroy($id)
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();

        return response()->json(null, 204);
    }

    public function scheduleReminder(Request $request)
    {
        $reminder = Reminder::create([
            'reminder_name' => $request->reminder_name,
            'medication_type' => $request->medication_type,
            'pills_number' => $request->pills_number,
            'time' => $request->time,
            'frequency' => $request->frequency,
            // Add other fields as needed
        ]);

        return response()->json(['message' => 'Reminder set successfully!']);
    }

}
