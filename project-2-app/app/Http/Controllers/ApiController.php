<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\RequiredRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function register(Request $request)
    {
//        $request->validate([
//            "name" => "required|string|between:2,100",
//            "email" => "required|string|email|max:100|unique:users",
//            "password" => "required|string|confirmed|min:6"
//        ]);

//        $request->validate([
//            "name" => "required|string|between:2,100",
//            "email" => "required|string|email|max:100|unique:users",
//            "password" => "required|string|confirmed|min:6"
//        ],[
//            "name.required" => "نام الزامی است.",
//            "name.string" => "نام باید رشته باشد.",
//            "name.between" => "طول نام بین ۲ تا ۱۰۰ باشد.",
//            "email.required" => "ایمیل الزامی است.",
//            "email.email" => "ایمیل درست وارد نشده است.",
//            "email.max" => "طول ایمیل نباید بیشتر از ۱۰۰ باشد.",
//            "email.unique:users" => "حسابی با این ایمیل موجود است.",
//            "password.required" => "پسورد الزامی است.",
//            "password.confirmed" => "تکرار پسورد نادرست است.",
//            "password.min"=>"طول پسورد حداقل باید ۶ باشد.",
//        ]);

//        dd('skskks');
        $validator = Validator::make($request->all(), [
            "name" => [new RequiredRule,'string', 'between:2,100'],
            "email" => [new RequiredRule,'string','email', 'max:100','unique:users'],
            "password" => [new RequiredRule, 'string', 'confirmed', 'min:6']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 401);
        }
//
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->assignRole($request->role);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'message' => "User created successfully.",
            "token" => $token,
            "user" => $user
        ]);
    }
}
