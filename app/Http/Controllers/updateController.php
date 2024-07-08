<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class updateController extends Controller
{
    public function banUser(Request $request)
    {
        $userId = $request->input('userId');

        $user = User::find($userId);
        return $user;
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->status = 'banned';
        $user->save();

        return response()->json(['message' => 'User banned successfully'], 200);
    }
}

