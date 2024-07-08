<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Paramedic;

class UpdateController extends Controller
{
    public function banUser(Request $request)
    {
        $staffId = $request->input('staffId');
        $role = $request->input('role');

        if ($role === 'doctor') {
            $staff = Doctor::find($staffId);
        } elseif ($role === 'paramedic') {
            $staff = Paramedic::find($staffId);
        } else {
            return response()->json(['message' => 'Invalid role'], 400);
        }

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $user = User::find($staff->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->status = 'banned';
        $user->save();

        $staff->account_status = 'banned';
        $staff->save();

        return response()->json(['message' => 'User and staff banned successfully'], 200);
    }

    public function unbanUser(Request $request)
    {
        $staffId = $request->input('staffId');
        $role = $request->input('role');

        if ($role === 'doctor') {
            $staff = Doctor::find($staffId);
        } elseif ($role === 'paramedic') {
            $staff = Paramedic::find($staffId);
        } else {
            return response()->json(['message' => 'Invalid role'], 400);
        }

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $user = User::find($staff->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->status = 'active';
        $user->save();

        $staff->account_status = 'active';
        $staff->save();

        return response()->json(['message' => 'User and staff unbanned successfully'], 200);
    }

    public function updateStaff(Request $request, $id)
    {
        $role = $request->input('role');
        $data = $request->all();

        if ($role === 'doctor') {
            $staff = Doctor::find($id);
            $userId = $staff->user_id; // Fetch the user ID
        } elseif ($role === 'paramedic') {
            $staff = Paramedic::find($id);
            $userId = $staff->user_id; // Fetch the user ID
        } else {
            return response()->json(['message' => 'Invalid role'], 400);
        }

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update the user table
        $user->email = $role === 'doctor' ? $data['doctor_email'] : $data['paramedic_staff_email'];
        $user->date_of_birth = $role === 'doctor' ? $data['doctor_date_of_birth'] : $data['paramedic_staff_date_of_birth'];
        $user->gender = $role === 'doctor' ? $data['doctor_gender'] : $data['paramedic_staff_gender'];
        $user->phone_number = $role === 'doctor' ? $data['doctor_phone_number'] : $data['paramedic_staff_phone_number'];
        $user->username = $role === 'doctor' ? $data['doctor_name'] : $data['paramedic_staff_name'];
        $user->identity_card_number = $role === 'doctor' ? $data['doctor_identity_card_number'] : $data['paramedic_staff_identity_card_number'];
        $user->save();
        // Update the specific staff table
        $staff->fill($data);
        $staff->save();

        return response()->json([
            'message' => 'Staff updated successfully',
            'data' => $staff,
            'user' => $user // Return the user data in the response
        ], 200);
    }
}
