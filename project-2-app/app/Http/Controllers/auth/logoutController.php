<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class logoutController extends Controller
{
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => "User logged out successfully."]);
    }
}
