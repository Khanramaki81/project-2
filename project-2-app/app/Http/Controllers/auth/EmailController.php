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
        $email = $request->login;
        if($email){
            $code =  rand(1000, 9999);
            OtpCode::create([
                'login'=>$email,
                'code'=>$code,
                'expired_at'=>Carbon::now()->addMinutes(10),
            ]);
            if(env('MAIL_STATUS')){
                Mail::to($email)->send(new VerifyCodeEmail($code));
            }
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Send Code To Email',
            ]);
        }else
            return response()->json([
                'success' => false,
            ],  status: 422);

    }

    public function verifyCode(EmailCode $request){
        $user = User::firstWhere('email',$request->login);
        if($user){
            $code = OtpCode::firstWhere('login',$user->email);
            if($code->code == $request->code){
                if($code->expired_at > Carbon::now()){
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

    public function generateOtp(generateEmail $request){
        $user = User::firstWhere('email',$request->login);
        $email = $user->email;
        if($user){
            $code =  rand(1000, 9999);
            $otp = OtpCode::firstWhere('login',$user->login);
            if($otp){
                $otp->update([
                    'attempt' => $otp->attempt+1,
                    'code'=>$code,
                ]);
            }else{
                OtpCode::create([
                    'login'=>$user->email,
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
                'message' => 'Send Code To Email',
            ]);
        }else
            return response()->json([
                'success' => false,
            ],  status: 422);
    }
}
