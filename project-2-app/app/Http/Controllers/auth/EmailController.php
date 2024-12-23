<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\otp\generate\generateEmail;
use App\Http\Requests\auth\otp\verify\EmailCode;
use App\Mail\VerifyCodeEmail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function sendEmail(generateEmail $request){
        $user = User::firstWhere('email',$request->email);
        $email = $user->email;
        if($user){
            $code =  rand(1000, 9999);
            $otp = OtpCode::firstWhere('login_method_value',$user->email);
            if($otp){
                $otp->update([
                    'attempt' => $otp->attempt+1,
                    'code'=>$code,
                ]);
            }else{
                OtpCode::create([
                    'login_method_value' => $user->email,
                    'login_method'=> 'email',
                    'code'=>$code,
                    'expired_at'=>Carbon::now()->addMinutes(10),
                ]);
            }
            if(env('MAIL_STATUS')){
                Mail::to($email)->send(new VerifyCodeEmail($code));
            }
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Send Code To Email Successfully',
            ]);
        }else
            return response()->json([
                'success' => false,
            ],  status: 422);
    }

    public function verifyCode(EmailCode $request){
        $user = User::firstWhere('email',$request->email);
        if($user){
            $code = OtpCode::firstWhere([
                ['login_method_value',$user->email],
                ['login_method', 'email']
            ]);
            if($code->code == $request->code){
                if($code->expired_at > Carbon::now()){
                    if(!$user->email_verified_at){
                        $user->markEmailAsVerified();
                    }
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->plainTextToken;
                    $code->delete();
                    return response()->json([
                       'success' => true,
                       'data' => [
                           'token' => $token,
                           'user' => $user->only('name','email','created_at'),
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
}
