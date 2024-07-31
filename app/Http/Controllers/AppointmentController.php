<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient; // Ensure you have a Patient model
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WaitingList;

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
            'patient_name' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id',
            'status' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Determine the status based on the user's role
        $status = 'pending'; // Default status
        if ($user->user_role == 'paramedic') {
            $status = 'accepted';
        }

        $appointment = new Appointment([
            'creator_id' => $user->id,
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'type' => $request->type,
            'blood_type' => $request->blood_type,
            'details' => $request->details,
            'paramedic_id' => $request->paramedic_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'patient_id' => $request->patient_id,
            'status' => $status
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

    /**
     * Display the specified appointment based on patient ID.
     *
     * @param  int  $patientId
     * @return \Illuminate\Http\Response
     */
    // public function getAppointmentsByUserIdAndPatientId(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'patient_id' => 'required|exists:patients,id',
    //     ]);

    //     // Fetch appointments where the user is the creator or the appointment is for the user
    //     $appointments = Appointment::with(['creator', 'paramedic', 'doctor', 'patient'])
    //                                 ->where(function ($query) use ($request) {
    //                                     $query->where('creator_id', $request->user_id)
    //                                         ->orWhere('patient_id', $request->patient_id);
    //                                 })
    //                                 ->get();

    //     return response()->json($appointments);
    // }

    public function getAppointmentsByUserIdAndPatientId(Request $request)
    {
        $userId = auth()->id();
        $patientId = $request->query('patient_id');

        $appointments = Appointment::with(['creator', 'paramedic', 'doctor', 'patient'])
                                    ->where(function ($query) use ($userId, $patientId) {
                                        $query->where('creator_id', $userId)
                                            ->orWhere('patient_id', $patientId);
                                    })
                                    ->get();

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

    /**
     * Get all pending appointments.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingAppointments()
    {
        $appointments = Appointment::where('status', 'pending')->get();
        return response()->json($appointments);
    }

    /**
     * Update the appointment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,completed,rejected',
            'reason' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id',
            'paramedic_id' => 'nullable|exists:paramedic_staff,id',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        if ($request->status === 'accepted') {
            $appointment->doctor_id = $request->doctor_id;
            $appointment->paramedic_id = $request->paramedic_id;
        } elseif ($request->status === 'rejected') {
            $appointment->reason = $request->reason;
        }
        $appointment->save();

        return response()->json($appointment);
    }
    

    public function getAcceptedAppointmentsParamedic()
    {
        $waitingListAppointmentIds = WaitingList::pluck('appointment_id')->toArray();
    
        $appointments = Appointment::where('status', 'accepted')
                                    ->whereNotIn('id', $waitingListAppointmentIds)
                                    ->with(['patient', 'doctor'])
                                    ->get();
    
        return response()->json($appointments);
    }    

    public function getAcceptedAppointments()
    {
        $userId = auth()->id();

        $acceptedAppointments = Appointment::where('status', 'accepted')
                                            ->where('creator_id', $userId)
                                            ->orWhereHas('patient', function($query) use ($userId) {
                                                $query->where('user_id', $userId);
                                            })
                                            ->with(['patient', 'doctor'])
                                            ->get();

        return response()->json($acceptedAppointments);
    }

    public function getPendingAndAcceptedAppointments()
    {
        $appointments = Appointment::whereIn('status', ['pending', 'accepted'])
                                    ->with(['patient', 'doctor'])
                                    ->get();
        return response()->json($appointments);
    }

    public function getAppointmentsSummary()
    {
        $appointments = Appointment::select('patient_name', 'type', 'date')->get();

        return response()->json($appointments);
    }

    public function getDoctorIdByUserId($userId)
    {
        $doctor = Doctor::where('user_id', $userId)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        return response()->json(['doctor_id' => $doctor->id]);
    }

    public function getAppointmentsByDoctorId($doctorId)
    {
        $appointments = Appointment::where('doctor_id', $doctorId)->with(['patient'])->get();
        return response()->json($appointments);
    }

    public function scheduleAppointment(Request $request)
    {
        $appointment = Appointment::create([
            'details' => $request->details,
            'date' => $request->date,
            'time' => $request->time,
            // Add other fields as needed
        ]);

        return response()->json(['message' => 'Appointment scheduled successfully!']);
    }
}
