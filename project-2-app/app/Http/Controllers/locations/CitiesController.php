<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/provinces/{province_id}",
     *     summary="show province with it's cities",
     *     description="show province with it's cities",
     *     tags={"Locations"},
     *     @OA\Parameter(
     *          description="province_id",
     *          in="path",
     *          name="province_id",
     *          required=true,
     *          example="27",
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Show province with it's cities",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean"),
     *              @OA\Property(property="province", type="object"),
     *              @OA\Property(property="cities", type="object"),
     *         ),
     *     ),
     * )
     */
    public function index($province_id){
        $province = Province::find($province_id);
        $cities = City::where('province_id', $province_id)->get('name');
        return response()->json([
            'success' => true,
            'province' => $province->name,
            'cities' => $cities,
        ]);
    }
}
