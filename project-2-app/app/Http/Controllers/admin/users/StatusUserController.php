<?php

namespace App\Http\Controllers\admin\users;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\UserStatusRequest;
use App\Models\User;
use Illuminate\Http\Request;

class StatusUserController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/api/admin/users/{user_id}/updateStatus",
     *     summary="update an existing User's status",
     *     description="update an existing User'hps status",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *           description="user_id",
     *           in="path",
     *           name="user_id",
     *           required=true,
     *           example="2",
     *      ),
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="is_blocked", type="boolean"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="user updated successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function update(UserStatusRequest $request,User $user_id){
        ACLHelper::ACL("edit_user_status");
        $user_id->update([
            'is_blocked' => $request->is_blocked
        ]);
        return response()->json([
            "success" => true,
            "message" => "user updated successfully",
        ]);
    }
}
