<?php

// App\Http\Controllers\WaitingListController.php

// App\Http\Controllers\WaitingListController.php

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
                                    ->orderBy('waiting_number')
                                    ->get();
        return response()->json($waitingList);
    }

    // public function addToWaitingList(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'appointment_id' => 'required|exists:appointments,id',
    //         'doctor_id' => 'required|exists:doctors,id',
    //         'patient_name' => 'required|string|max:255',
    //         'patient_id' => 'nullable|exists:patients,id',
    //     ]);

    //     $waitingNumber = WaitingList::max('waiting_number') + 1;

    //     $waitingListEntry = WaitingList::create([
    //         'appointment_id' => $validatedData['appointment_id'],
    //         'doctor_id' => $validatedData['doctor_id'],
    //         'patient_name' => $validatedData['patient_name'],
    //         'patient_id' => $validatedData['patient_id'],
    //         'waiting_number' => $waitingNumber,
    //         'status' => null,
    //     ]);

    //     return response()->json($waitingListEntry, 201);
    // }

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


    public function getDoctorWaitingList()
    {
        $doctorId = Auth::user()->doctor->id; // Assuming a one-to-one relationship between user and doctor
        $waitingList = WaitingList::with(['appointment', 'patient'])
                                    ->where('doctor_id', $doctorId)
                                    ->whereNull('status')
                                    ->orderBy('waiting_number')
                                    ->get();
        return response()->json($waitingList);
    }
}


