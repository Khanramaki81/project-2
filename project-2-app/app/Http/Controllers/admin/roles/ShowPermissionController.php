<?php

namespace App\Http\Controllers\admin\roles;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ShowPermissionController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/admin/roles/permissions",
     *     summary="show permissions",
     *     description="show permissions",
     *     tags={"Admin Roles & Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *           response=403,
     *           description="the user does not have this permission.",
     *           @OA\JsonContent(
     *                @OA\Property(property="message", type="string"),
     *           ),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="show permissions",
     *          @OA\JsonContent(
     *               @OA\Property(property="data", type="string", description="show permissions"),
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
        ACLHelper::ACL("show_permissions");
        $data = Permission::all('name', 'description');
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
}
//composer dump-autoload
