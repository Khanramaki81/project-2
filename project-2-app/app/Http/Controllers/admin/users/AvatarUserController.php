<?php

namespace App\Http\Controllers\admin\users;

use app\Helpers\ACLHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\users\AvatarUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Laravolt\Avatar\Facade as Avatar;

class AvatarUserController extends Controller
{
    /**
     * @param AvatarUserRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/admin/users/{user_id}/avatar",
     *     summary="store new avatar",
     *     description="Store new avatar and create image record in DB",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *           description="user_id",
     *           in="path",
     *           name="user_id",
     *           required=true,
     *           example="2",
     *      ),
     *      security={{"bearer":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                   @OA\Property(
     *                      property="avatar",
     *                      type="file",
     *                      description="The image to be uploaded."
     *                  ),
     *              ),
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
     *          response=201,
     *          description="image stored successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *               @OA\Property(property="url", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *          ),
     *      ),
     * )
     */
    public function store(AvatarUserRequest $request,User $user_id){
        ACLHelper::ACL("store_avatar");
        $file = $request->file('avatar');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        if($user_id->avatar){
            Storage::disk('public')->delete('avatars/' . $fileName);
            Storage::disk('public')->put('avatars/' . $fileName, file_get_contents($file));
            $url = asset('storage/avatars/' . $fileName);
            $user_id->update([
                'avatar' =>  $url,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'avatar updated successfully.',
                'url' => $url,
            ],status:201);
        }
        Storage::disk('public')->put('avatars/' . $fileName, file_get_contents($file));
        $url = asset('storage/avatars/' . $fileName);
        $user_id->avatar = $url;
        $user_id->save();
        return response()->json([
            "success" => true,
            "message" => 'avatar saved successfully.',
            "url" => $url,
        ],status:201);
    }


    /**
     * @OA\Delete(
     *     path="/api/admin/users/{user_id}/avatar",
     *     summary="delete an existing avatar image",
     *     description="delete an existing avatar image",
     *     tags={"Admin Users Management"},
     *     @OA\Parameter(
     *           description="user_id",
     *           in="path",
     *           name="user_id",
     *           required=true,
     *           example="2",
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=403,
     *          description="the user does not have this permission.",
     *          @OA\JsonContent(
     *                @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="image stored successfully",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *               @OA\Property(property="url", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *          ),
     *      ),
     * )
     */
    public function destroy(User $user_id){
        ACLHelper::ACL("delete_avatar");
        Storage::disk('public')->delete('avatars/' . $user_id->avatar);
        $user_id->avatar = null;
        $user_id->save();
        return response()->json([
            'success' => true,
            'message' => 'avatar deleted successfully.',
        ],200);
    }

}
