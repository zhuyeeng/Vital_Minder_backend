<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
        ]);

        $appointment->save();

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified appointment based on patient ID.
     *
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    public function showByPatientId($patientId)
    {
        $appointments = Appointment::where('creator_id', $patientId)->with(['creator', 'paramedic', 'doctor'])->get();
        return response()->json($appointments);
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        ]);

        $appointment = Appointment::findOrFail($id);

        $appointment->update($request->all());

        return response()->json($appointment);
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(null, 204);
    }
}
