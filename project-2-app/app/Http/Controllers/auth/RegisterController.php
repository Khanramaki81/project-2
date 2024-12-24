<?php
namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
//use App\Rules\RequiredRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Rejister a new user",
     *     description="Rejister a new user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="saeideh khanramaki"),
     *             @OA\Property(property="email", type="string", example="khanramaki@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Aa123456"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Aa123456"),
     *             @OA\Property(property="phone", type="string", example="09110000000"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the registered user."),
     *              @OA\property(property="user", type="object", description="User details."),
     *         ),
     *     ),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->only(['name','email','phone']);
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'message' => "User created successfully.",
            "token" => $token,
            "user" => $user
        ]);
    }
}
