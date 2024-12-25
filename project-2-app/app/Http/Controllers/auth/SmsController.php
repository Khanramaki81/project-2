<?php

namespace App\Http\Controllers\auth;

use App\Events\SmsVerifiCode;
use app\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\otp\generate\generateSMS;
use App\Http\Requests\auth\otp\verify\SMSCode;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SmsController extends Controller
{
    /**
     * @param generateSMS $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/sendSMS",
     *     summary="Send verifiction sms to user",
     *     description="Send verifiction sms to user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="09000000000"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="You have been logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Send Code To sms Successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Phone not found"),
     *         ),
     *     ),
     * )
     */
    public function sendSMS(generateSMS $request): \Illuminate\Http\JsonResponse
    {
        $user = User::firstWhere('phone', $request->phone);
        $mobile_number = $user->phone;
        if ($user) {
            $otp_token = str::random(32);
            $code = rand(1000, 9999);
            $otp = OtpCode::firstWhere('login_method_value', $user->phone);
            if ($otp) {
                $otp->update([
                    'attempt' => $otp->attempt + 1,
                    'code' => $code,
                    'otp_token' => $otp_token,
                ]);
            } else {
                OtpCode::create([
                    'login_method_value' => $user->phone,
                    'login_method' => 'phone',
                    'code' => $code,
                    'otp_token' => $otp_token,
                    'expired_at' => Carbon::now()->addMinutes(10),
                ]);
            }
            if (env('SMS_STATUS')) {
                SmsHelper::sendSms($mobile_number, $code);
            }
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Send Code To SMS Successfully',
            ]);
        } else
            return response()->json([
                'success' => false,
            ], status: 422);
    }

    /**
     * @param SMSCode $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/verifySMS",
     *     summary="Send verifiction code to server",
     *     description="Send verifiction code to server",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="09000000000"),
     *             @OA\Property(property="code", type="string", example="5432"),
     *             @OA\Property(property="otp_token", type="string", example="pfjV8IqaO12Odgofw7Pm6lP1ResAR4dL"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="You have been logged in successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the registered user"),
     *              @OA\Property(property="user", type="object", description="User details"),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Code Expired",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Code Invalid",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function verifyCode(SMSCode $request){
        $user = User::firstWhere('phone',$request->phone);
        if($user){
            $code = OtpCode::firstWhere([
                ['login_method_value',$user->phone],
                ['login_method', 'phone'],
                ['otp_token',$request->otp_token]
            ]);
            if($code){
                if($code->code == $request->code){
                    if($code->expired_at > Carbon::now()){
                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->plainTextToken;
                        $code->delete();
                        return response()->json([
                            'success' => true,
                            'data' => [
                                'token' => $token,
                                'user' => $user->only('name','email','phone','created_at'),
                            ]
                        ]);
                    }else{
                        $code->delete();
                        return response()->json([
                            'success' => false,
                            'message' => 'Code Expired'
                        ],status: 403);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Code Invalid'
                    ],status: 400);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => [
                        'not_found' => 'Phone does not match'
                    ],
                ],status: 404);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => [
                    'not_found' => 'The phone is incorrect'
                ],
            ],status:404);
        }
    }
}
