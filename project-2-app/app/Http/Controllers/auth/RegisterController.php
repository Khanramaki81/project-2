<?php
namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
//use App\Rules\RequiredRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $create = $request->only(['name','email','phone']);
        $create['password'] = bcrypt($request->password);
        $user = User::create($create);

        if($request->role == "admin"){
            $user->assignRole($request->role);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'message' => "User created successfully.",
            "token" => $token,
            "user" => $user
        ]);
    }
}
