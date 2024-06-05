<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\User;

class authController extends Controller
{
    function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required'
        ]);

        if($validator->fail()){
            return response()->json([
                'status'=>'false',
                'data'=>$validator->errors()
            ]);
        }else{
            $user = User::create([
                'name'->$request->name,
                'email'->$request->email,
                'password'->$request->password
            ]);

            return response()->json([
                'status'=>'true',
                'message'=>'User Register Successful',
                'data'=>$user->createToken('register_token')->plainTextToken
            ]);
        }
    }
}
