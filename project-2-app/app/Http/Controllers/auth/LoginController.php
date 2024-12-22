<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = request(['email', 'password']);
        if(!auth()->attempt($credentials)){
            return response()->json([
                "message" => "Unauthorized, check your login credentials."
            ],401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            "token" => $token,
            "user" => $user,
            "message" => "You have been logged in successfully."
        ]);
    }
}
