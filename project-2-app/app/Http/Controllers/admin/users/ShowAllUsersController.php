<?php

namespace App\Http\Controllers\admin\users;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Query\Builder;
//use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ShowAllUsersController extends Controller
{
    /**
     *
     * @OA\Get(
     *     path="/api/admin/users/",
     *     summary="show all users to admin",
     *     description="show all users to admin",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *            description="choose: **true|false**",
     *            in="query",
     *            name="is_admin",
     *     ),
     *     @OA\Parameter(
     *           description="status: **is_active|is_blocked**",
     *           in="query",
     *           name="status",
     *           @OA\Schema (
     *               example="is_active",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           description="user email",
     *           in="query",
     *           name="email",
     *           @OA\Schema (
     *                example="name@gmail.com",
     *           ),
     *      ),
     *      @OA\Parameter(
     *           description="user cell phone",
     *           in="query",
     *           name="cell_phone_number",
     *           @OA\Schema (
     *               example="09000000000",
     *           ),
     *       ),
     *       @OA\Parameter(
     *           description="user name",
     *           in="query",
     *           name="name",
     *           @OA\Schema (
     *               example="name",
     *           ),
     *       ),
     *       @OA\Parameter(
     *            description="list of users start from",
     *            in="query",
     *            name="data_from",
     *            @OA\Schema (
     *                example="2025-01-07T18:18:28",
     *            ),
     *       ),
     *       @OA\Parameter(
     *            description="list of users data end to",
     *            in="query",
     *            name="data_to",
     *            @OA\Schema (
     *                 example="2025-01-07T18:18:28",
     *            ),
     *       ),
     *       @OA\Parameter(
     *            description="page",
     *            in="query",
     *            name="page",
     *            @OA\Schema (
     *                example="1",
     *            ),
     *       ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Access token for the created user."),
     *              @OA\Property(property="data", type="string", description="Access token for the created user."),
     *         ),
     *     ),
     * )
     */
    public function index(Request $request){
        ACLHelper::ACL("show_all_users");
        $query = User::query();
        if($request->filled('is_admin')) {
            if ($request->is_admin=="true") {
                $query->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin']);
                });
            }
            //اینجا مشکل دارم
            if($request->is_admin=="false") {
                $query = $query->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'admin');
                })->orWhereDoesntHave('roles');
            }
        }

        if($request->filled('status')){
            if($request->status == 'is_active') {
                $query->where('is_blocked', false);
            }
            if($request->status == 'is_blocked') {
                $query->where('is_blocked', true);
            }
        }

        if($request->filled('email')){
            $query->where('email',$request->email);
        }

        if($request->filled('cell_phone_number')){
            $query->where('phone',$request->cell_phone_number);
        }

        if($request->filled('name')){
            $query->where('name',$request->name);
        }


        if($request->filled('data_from')&&$request->filled('data_to')){
            $query->whereBetween('created_at',[$request->data_from.'.000000Z',$request->data_to.'.000000Z']);
        }

        if($request->filled('data_from')){
            $query->where('created_at', '>=',$request->data_from.'.000000Z');
        }

        if($request->filled('data_to')){
            $query->where('created_at','<=',$request->data_to.'.000000Z');
        }

        $query = $query->paginate(5);
        $countPage= $query->lastPage();
        if($countPage < $request->page){
            return response()->json([
                'message' => "page out of range",
            ],200);
        }
        return response()->json([
            'data' => $query->items(),
        ],200);
    }

}



