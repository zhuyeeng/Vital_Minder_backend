<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'time' => 'required',
            'details' => 'nullable|string',
            'location' => 'required|string',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'time' => $request->time,
            'details' => $request->details,
            'location' => $request->location,
            'status' => 'pending',
        ]);

        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::find($id);

        if (is_null($appointment)) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (is_null($appointment)) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'details' => 'nullable|string',
            'location' => 'required|string',
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $appointment->update($request->all());

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (is_null($appointment)) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
