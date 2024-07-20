<?php

namespace App\Http\Controllers;

use App\Models\MedicationReport;
use Illuminate\Http\Request;

class MedicationReportController extends Controller
{
    // Store a new medication report
    public function storeMedicationReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'created_by' => 'required|exists:doctors,id',
            'paramedic_staff_id' => 'nullable|exists:paramedic_staff,id',
            'report_title' => 'required|string|max:255',
            'report_created_date' => 'required|date_format:Y-m-d H:i:s',
            'physical_examination_note' => 'required|string',
            'diagnostic_tests_results' => 'nullable|string',
            'treatment_plan_instruction' => 'required|string',
            'doctor_note' => 'nullable|string',
            'report_status' => 'required|in:ended,pending',
            'patient_name' => 'required|string|max:255', // Add this line
        ]);

        $medicationReport = MedicationReport::create($request->all());

        return response()->json(['message' => 'Medication report created successfully', 'data' => $medicationReport], 201);
    }

    // Update the report status to ended
    public function updateReportStatus(Request $request, MedicationReport $medicationReport)
    {
        $request->validate([
            'report_status' => 'required|in:ended,pending',
        ]);

        $medicationReport->update(['report_status' => $request->report_status]);

        return response()->json(['message' => 'Report status updated successfully', 'data' => $medicationReport], 200);
    }

    // Show a single medication report
    public function showMedicationReport(MedicationReport $medicationReport)
    {
        $medicationReport->load('creator');
        return response()->json(['data' => $medicationReport], 200);
    }

    // List all medication reports
    public function index()
    {
        $medicationReports = MedicationReport::with('creator')->get();

        return response()->json(['data' => $medicationReports], 200);
    }

    // Method to get medication reports by patient ID
    public function getMedicationReportsByPatientId($patientId)
    {
        $medicationReports = MedicationReport::with('creator')->where('patient_id', $patientId)->get();
        return response()->json($medicationReports);
    }
}

