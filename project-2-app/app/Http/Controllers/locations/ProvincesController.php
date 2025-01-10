<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvincesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/provinces",
     *     summary="show all provinces",
     *     description="show all provinces",
     *     tags={"Locations"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="show all provinces",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean"),
     *              @OA\Property(property="data", type="object"),
     *         ),
     *     ),
     * )
     */
    public function index(){
        $provinces = Province::get('name');
        return response()->json([
            'success' => true,
            'data' => $provinces,
        ]);
    }
}
