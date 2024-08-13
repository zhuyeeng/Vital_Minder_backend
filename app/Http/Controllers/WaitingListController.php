<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaitingList;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class WaitingListController extends Controller
{
    public function getWaitingList()
    {
        $waitingList = WaitingList::with(['patient', 'doctor', 'appointment'])
                                    ->where(function($query) {
                                        $query->whereNull('status')
                                            ->orWhere('status', 'pending');
                                    })
                                    ->orderBy('waiting_number')
                                    ->get();
        return response()->json($waitingList);
    }

    public function addToWaitingList(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        $maxWaitingNumber = WaitingList::max('waiting_number');
        $waitingNumber = $maxWaitingNumber ? $maxWaitingNumber + 1 : 1;

        $waitingList = WaitingList::create([
            'appointment_id' => $request->appointment_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'patient_id' => $request->patient_id,
            'waiting_number' => $waitingNumber,
        ]);

        return response()->json($waitingList);
    }


    public function getDoctorWaitingList($doctorId)
    {
        $waitingList = WaitingList::with(['appointment', 'patient'])
                                    ->where('doctor_id', $doctorId)
                                    ->whereNull('status')
                                    ->orderBy('waiting_number')
                                    ->get();

        return response()->json($waitingList);
    }

    // Public function updateStatus(Request $request, $appointmentId)
    // {
    //     $request->validate([
    //         'status' => 'required|string',
    //     ]);

    //     $waitingList = WaitingList::where('appointment_id', $appointmentId)->firstOrFail();
    //     $waitingList->status = $request->status;
    //     $waitingList->save();

    //     return response()->json(['message' => 'Waiting list status updated successfully', 'data' => $waitingList], 200);
    // }

    public function updateStatus(Request $request, $appointmentId)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        // Update the waiting list status
        $waitingList = WaitingList::where('appointment_id', $appointmentId)->firstOrFail();
        $waitingList->status = $request->status;
        $waitingList->save();

        // Update the appointment status
        $appointment = Appointment::where('id', $appointmentId)->firstOrFail();
        $appointment->status = $request->status; // Assuming you want the appointment status to match the waiting list status
        $appointment->save();

        return response()->json([
            'message' => 'Waiting list and appointment status updated successfully',
            'data' => [
                'waitingList' => $waitingList,
                'appointment' => $appointment
            ]
        ], 200);
    }
}


