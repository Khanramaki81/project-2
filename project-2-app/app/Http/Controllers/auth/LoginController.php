<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     description="User login",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="saeideh@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Aa123456"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="You have been logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Access token for the registered user"),
     *             @OA\Property(property="user", type="object", description="User details"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validatiob Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Validatiob error"),
     *         ),
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::firstWhere('email',$request->email);
        if(Hash::check($request->password, $user->password)){
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            return response()->json([
                "token" => $token,
                "user" => $user,
                "message" => "You have been logged in successfully."
            ]);
        }
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'password is invalid',
        ]);

    }
}
