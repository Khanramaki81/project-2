<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class logoutController extends Controller
{
    /**
     *
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="User Logout",
     *     description="Revokes the current user's access token.",
     *     tags={"auth"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged out successfully."),
     *         ),
     *     ),
     * )
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => "User logged out successfully.",
        ],200);
    }
}
