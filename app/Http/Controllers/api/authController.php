<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        } else {
            // Create User Record
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

            // Retrieve the user by email to ensure it was created and get the ID
            $createdUser = User::where('email', $request->email)->first();

            // Ensure the user creation was successful
            if ($createdUser && $createdUser->id) {
                // Create Patient Record
                $patient = Patient::create([
                    'user_id' => $createdUser->id,
                    'username' => $createdUser->username,
                    'phone_number' => $createdUser->phone_number,
                    'email' => $createdUser->email,
                    'password' => $createdUser->password,
                    'gender' => $createdUser->gender,
                    'date_of_birth' => $createdUser->date_of_birth,
                    'identity_card_number' => $createdUser->identity_card_number
                ]);

                // Create Token
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 'true',
                    'message' => 'User Register Successful',
                ]);
            } else {
                Log::error('User ID is null after user creation.');
                return response()->json([
                    'status' => 'false',
                    'message' => 'User ID is null after user creation'
                ], 500);
            }
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

        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create Token
        try {
            // Create the actual token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'true',
                'message' => 'Login Successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Token creation failed', ['exception' => $e->getMessage()]);
            return response()->json([
                'status' => 'false',
                'message' => 'Token creation failed',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }
}
