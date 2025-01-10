<?php

namespace App\Http\Controllers\admin\roles;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\roles\AssignRoleToUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/roles/{id}",
     *     summary="show user roles with id",
     *     description="show user roles with id",
     *     tags={"Admin Roles & Permissions"},
     *     @OA\Parameter(
     *          description="id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1",
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="show user roles",
     *          @OA\JsonContent(
     *               @OA\Property(property="data", type="string", description="show user roles"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function index($id)
    {
        ACLHelper::ACL("show_user_roles");
        $user = User::find($id);
        if($user){
            $roles = $user->roles->pluck('name')->toArray();
            if($roles){
                return response()->json([
                    'success' => true,
                    'data' => $roles,
                ],status:200);
            }
            return response()->json([
                'success' => false,
                'message' => "The user has no role.",
            ],status:404);
        }
        return response()->json([
            'success' => false,
            'message' => "User not found.",
        ],status:404);
    }

    /**
     * @param AssignRoleToUserRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/admin/roles/assignRoleToUser",
     *     summary="role assigned to user",
     *     description="role assigned to user",
     *     tags={"Admin Roles & Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="role_id", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="3"),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="role assigned to user successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="role assigned to user successfully"),
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
    public function store(AssignRoleToUserRequest $request)
    {
        ACLHelper::ACL("assign_user_role");
        $role = Role::findById($request->role_id);
        $user = User::find($request->user_id);
        $user->assignRole($role->name);
        return response()->json([
            'success' => true,
            'message' => "role assigned to user successfully",
        ], status: 201);
    }

    /**
     * @param AssignRoleToUserRequest $request
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/admin/roles/assignRoleToUser",
     *     summary="delete user role",
     *     description="delete user role",
     *     tags={"Admin Roles & Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="role_id", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="3"),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="user role successfully deleted",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="user role successfully deleted"),
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
    public function destroy(AssignRoleToUserRequest $request)
    {
        ACLHelper::ACL("delete_user_roles");
        $role = Role::findById($request->role_id);
        $user = User::find($request->user_id);
        $user->removeRole($role->name);
        return response()->json([
            'success' => true,
            'message' => "user role successfully deleted",
        ], status: 201);

    }
}
