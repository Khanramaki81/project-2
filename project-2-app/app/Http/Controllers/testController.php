<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
   public function index(){
       dd("this is a test controller.");
   }
}

//*     @OA\Parameter(
//     *          description="status: **is_active|is_blocked**",
//     *          in="query",
//     *          name="status",
//     *          @OA\Schema (
//     *              type="string",
//     *              example="is_active",
//     *          ),
//     *     ),
//     *     @OA\Parameter(
//     *          description="user email",
//     *          in="query",
//     *          name="email",
//     *          @OA\Schema (
//     *              type="string",
//     *          ),
//     *     ),
//     *     @OA\Parameter(
//     *          description="user cell phone",
//     *          in="query",
//     *          name="cell_phone_number",
//     *          @OA\Schema (
//     *              type="string",
//     *          ),
//     *      ),
//     *      @OA\Parameter(
//     *          description="user name",
//     *          in="query",
//     *          name="name",
//     *          @OA\Schema (
//     *              type="string",
//     *          ),
//     *      ),
//     *      @OA\Parameter(
//     *           description="list of users start from",
//     *           in="query",
//     *           name="data_from",
//     *           @OA\Schema (
//     *               type="string",
//     *               example:"2025-01-07T18:18:28",
//     *           ),
//     *      ),
//     *      @OA\Parameter(
//     *           description="list of users data end to",
//     *           in="query",
//     *           name="data_to",
//     *           @OA\Schema (
//     *               type="string",
//     *               example:"2025-01-07T18:18:28",
//     *           ),
//     *      ),
//     *      @OA\Parameter(
//     *           description="page",
//     *           in="query",
//     *           name="page",
//     *           @OA\Schema (
//     *               type="integer",
//     *               example:"1",
//     *           ),
//     *      ),
