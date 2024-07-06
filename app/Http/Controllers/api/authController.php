<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'identity_card_number' => 'required|unique:users,identity_card_number'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'user_role' => 'patient', // Assuming the role is patient for this example
            'identity_card_number' => $request->identity_card_number,
        ]);

        $createdUser = User::where('email', $request->email)->first();

        if ($createdUser && $createdUser->id) {
            Patient::create([
                'user_id' => $createdUser->id,
                'username' => $createdUser->username,
                'phone_number' => $createdUser->phone_number,
                'email' => $createdUser->email,
                'password' => $createdUser->password,
                'gender' => $createdUser->gender,
                'date_of_birth' => $createdUser->date_of_birth,
                'identity_card_number' => $createdUser->identity_card_number
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'true',
                'message' => 'User Register Successful',
                'token' => $token
            ]);
        } else {
            Log::error('User ID is null after user creation.');
            return response()->json([
                'status' => 'false',
                'message' => 'User ID is null after user creation'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'true',
            'message' => 'Login Successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
