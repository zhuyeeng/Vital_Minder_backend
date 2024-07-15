<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WaitingList;

class WaitingListController extends Controller
{
    public function getWaitingList()
    {
        $waitingList = WaitingList::with(['patient', 'doctor'])
                                    ->orderBy('waiting_number')
                                    ->get();
        return response()->json($waitingList);
    }

    public function addToWaitingList(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_id' => 'nullable|exists:patients,id', // patient_id can be null
        ]);
    
        $waitingNumber = WaitingList::max('waiting_number') + 1;
    
        $waitingListEntry = WaitingList::create([
            'appointment_id' => $validatedData['appointment_id'],
            'doctor_id' => $validatedData['doctor_id'],
            'patient_name' => $validatedData['patient_name'],
            'patient_id' => $validatedData['patient_id'],
            'waiting_number' => $waitingNumber,
        ]);
    
        return response()->json($waitingListEntry, 201);
    }

}
