<?php

namespace App\Http\Controllers\admin\roles;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\roles\CreateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/admin/roles",
     *     summary="show roles",
     *     description="show roles",
     *     tags={"Admin Roles & Permissions"},
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
     *          description="show roles",
     *          @OA\JsonContent(
     *               @OA\Property(property="data", type="string", description="show roles"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="data not found"),
     *         ),
     *     ),
     * )
     */
    public function index(){
        ACLHelper::ACL("show_roles");
        $data = Role::all('name', 'description');
        if($data){
            return response()->json([
                'success' => true,
                'data' => $data,
            ],status:200);
        }
        return response()->json([
            'success' => false,
            'message' => "data not found.",
        ],status:404);
    }

    /**
     * @param CreateRoleRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/admin/roles",
     *     summary="create role",
     *     description="create role",
     *     tags={"Admin Roles & Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="auther"),
     *             @OA\Property(property="description", type="string", example="می تواند وبلاگ را مدیریت کند."),
     *             @OA\Property(property="permissions", type="array",@OA\Items(type="integer"), example="[1,2,3]"),
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
     *          description="Role created successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="Role created successfully"),
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
    public function store(CreateRoleRequest $request){
        ACLHelper::ACL("create_role");
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $permissions = Permission::findMany($request->permissions );
        $role->syncPermissions($permissions);
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
        ],status:201);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/roles/{id}",
     *     summary="delete role",
     *     description="delete role",
     *     tags={"Admin Roles & Permissions"},
     *     @OA\Parameter(
     *          description="id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="2",
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
     *          response=204,
     *          description="Role deleted successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="Role deleted successfully."),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Role not found."),
     *         ),
     *     ),
     * )
     */
    public function destroy($id)
    {
        ACLHelper::ACL("delete_role");
        $role = Role::destroy($id);
        if($role){
            return response()->json([
                'success' => true,
                'message' => "Role deleted successfully",
            ],status:204);
        }
        return response()->json([
            'success' => false,
            'message' => "Role not found",
        ],status:404);
    }

    /**
     *
     * @param CreateRoleRequest $request
     * @return JsonResponse
     *
     * @OA\Patch(
     *     path="/api/admin/roles/{id}",
     *     summary="update role",
     *     description="update role",
     *     tags={"Admin Roles & Permissions"},
     *     @OA\Parameter(
     *           description="id",
     *           in="path",
     *           name="id",
     *           required=true,
     *           example="3",
     *      ),
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="auther"),
     *             @OA\Property(property="description", type="string", example="می تواند وبلاگ را مدیریت کند."),
     *             @OA\Property(property="permissions", type="array",@OA\Items(type="integer"), example="[1,2,3]"),
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
     *          description="Role updated successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="Role updated successfully"),
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
    public function update(CreateRoleRequest $request,$id){
        ACLHelper::ACL("edit_role");
        if(Role::find($id)){
            Role::findById($id)->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
            ],status:201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Role not found.',
        ],status:404);
    }
}
