<?php

namespace App\Http\Controllers\auth;

use App\Events\SmsVerifiCode;
use app\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\otp\generate\generateSMS;
use App\Http\Requests\auth\otp\verify\SMSCode;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SmsController extends Controller
{
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
                        ]);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Code Invalid'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => [
                        'not_found' => 'user not found'
                    ],
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => [
                    'not_found' => 'User Not Found'
                ],
            ]);
        }
    }
}
