<?php

namespace App\Http\Controllers\admin\users;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\CreateUpdateUsersRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users/{user_id}",
     *     summary="show user",
     *     description="show user",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *           description="user_id",
     *           in="path",
     *           name="user_id",
     *           required=true,
     *           example="2",
     *      ),
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
     *          description="show user info",
     *          @OA\JsonContent(
     *               @OA\Property(property="data", type="object", description="Show user info"),
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
    public function show($user_id){
        ACLHelper::ACL("show_user");
        $user = User::find($user_id);
        if($user){
            return response()->json([
                'success' => true,
                'data' => $user,
            ],status:200);
        }
        return response()->json([
            'success' => false,
            'message' => 'user not found.',
        ],status:404);
    }

    /**
     * @param CreateUpdateUsersRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/admin/users/",
     *     summary="create a new user",
     *     description="create a new user",
     *     tags={"Admin Users Management"},
     *     security={{"bearer":{}}},
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
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the created user."),
     *              @OA\property(property="user", type="object", description="User details."),
     *         ),
     *     ),
     * )
     */
    public function store(CreateUpdateUsersRequest $request){
        ACLHelper::ACL("create_user");
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            "success" => true,
            "message" => "User created successfully.",
            "token" => $token,
            "user" => $user
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{user_id}",
     *     summary="delete user",
     *     description="delete user",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *           description="user_id",
     *           in="path",
     *           name="user_id",
     *           required=true,
     *           example="2",
     *      ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="delete user info",
     *          @OA\JsonContent(
     *               @OA\Property(property="data", type="object", description="Delete user info"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function destroy($user_id){
        ACLHelper::ACL("delete_user");
        $user = User::find($user_id);
        if($user){
            $user->delete($user);
            return response()->json([
                'success' => true,
                'message' => "User deleted successfully",
            ], status: 204);
        }
        return response()->json([
            'success' => false,
            'message' => "user not found.",
        ], status: 404);
    }

    /**
     * @param CreateUpdateUsersRequest $request
     * @return JsonResponse
     *
     * @OA\Patch(
     *     path="/api/admin/users/{user_id}",
     *     summary="update an existing user",
     *     description="update an existing user",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *          description="user_id",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          example="2",
     *      ),
     *     security={{"bearer":{}}},
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
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update an existing User",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the updated user."),
     *              @OA\property(property="user", type="object", description="User details."),
     *         ),
     *     ),
     * )
     */
    public function update(CreateUpdateUsersRequest $request,User $user_id){
            ACLHelper::ACL("edit_user");
            $user_id->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
            ],status:200);
    }
}

