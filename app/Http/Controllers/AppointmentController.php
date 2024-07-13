<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient; // Ensure you have a Patient model
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // ... existing methods

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|string',
            'blood_type' => 'required|string',
            'details' => 'required|string',
            'paramedic_id' => 'nullable|exists:paramedic_staff,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_name' => 'required|string', // Add this validation
            'patient_id' => 'nullable|exists:patients,id', // Update to nullable
        ]);

        $appointment = new Appointment([
            'creator_id' => Auth::id(),
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'type' => $request->type,
            'blood_type' => $request->blood_type,
            'details' => $request->details,
            'paramedic_id' => $request->paramedic_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name, // Add this field
            'patient_id' => $request->patient_id, // Include patient_id
        ]);

        $appointment->save();

        return response()->json($appointment, 201);
    }

    /**
     * Fetch patient ID using user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getPatientIdByUserId($userId)
    {
        $patient = Patient::where('user_id', $userId)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json(['patient_id' => $patient->id, 'user_id' => $userId]);
    }

    /**
     * Display the specified appointment based on patient ID.
     *
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function showByUserId($userId)
    {
        // Find the patient ID associated with the user ID
        $patient = Patient::where('user_id', $userId)->firstOrFail();

        // Fetch appointments for the found patient ID including patient, doctor, and paramedic details
        $appointments = Appointment::with(['creator', 'paramedic', 'doctor', 'patient'])
                                    ->where('patient_id', $patient->id)
                                    ->get();

        // Return the appointments data with the related details
        return response()->json($appointments);
    }

    public function showByCreatorId($userId)
    {
        // Fetch appointments for the given creator ID including patient, doctor, and paramedic details
        $appointments = Appointment::with(['creator', 'paramedic', 'doctor', 'patient'])
                                    ->where('creator_id', $userId)
                                    ->get();

        // Return the appointments data with the related details
        return response()->json($appointments);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required',
            'type' => 'sometimes|required|string',
            'blood_type' => 'sometimes|required|string',
            'details' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,accepted,completed,rejected',
            'reason' => 'nullable|string',
            'paramedic_id' => 'nullable|exists:paramedic_staff,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_name' => 'sometimes|required|string',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        $appointment = Appointment::findOrFail($id);

        $appointment->update($request->all());

        return response()->json($appointment);
    }

    // ... other existing methods
}
