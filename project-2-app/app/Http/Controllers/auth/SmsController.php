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

class SmsController extends Controller
{
    public function sendSMS(generateSMS $request)
    {
        $mobile_number = $request->login;
        if($mobile_number){
            $code =  rand(1000, 9999);
            OtpCode::create([
                'login'=>$mobile_number,
                'code'=>$code,
                'expired_at'=>Carbon::now()->addMinutes(10),
            ]);
            if(env('SMS_STATUS')){
                SmsHelper::sendSms($mobile_number, $code);
            }
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Send Code To SMS Successfully',
            ]);
        }else
            return response()->json([
                'success' => false,
            ],  status: 422);
    }
    public function verifyCode(SMSCode $request){
        $user = User::firstWhere('phone',$request->login);
        if($user){
            $code = OtpCode::firstWhere('login',$user->phone);
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
                    'not_found' => 'User Not Found'
                ],
            ]);
        }
    }
    public function generateOtp(generateSMS $request)
    {
        $user = User::firstWhere('phone', $request->login);
        $mobile_number = $user->phone;
        if ($user) {
            $code = rand(1000, 9999);
            $otp = OtpCode::firstWhere('login', $user->login);
            if ($otp) {
                $otp->update([
                    'attempt' => $otp->attempt + 1,
                    'code' => $code,
                ]);
            } else {
                OtpCode::create([
                    'login' => $user->phone,
                    'code' => $code,
                    'expired_at' => Carbon::now()->addMinutes(10),
                ]);
            }
            if (env('SMS_STATUS')) {
                SmsHelper::sendSms($mobile_number, $code);
            }
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Send Code To Email',
            ]);
        } else
            return response()->json([
                'success' => false,
            ], status: 422);
    }
}
