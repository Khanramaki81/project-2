<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\otp\generate\generateEmail;
use App\Http\Requests\auth\otp\verify\EmailCode;
use App\Mail\VerifyCodeEmail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    /**
     * @param generateEmail $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/sendEmail",
     *     summary="Send verifiction email to user",
     *     description="Send verifiction email to user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="saeideh@gmail.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="You have been logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Send Code To Email Successfully."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Email not found."),
     *         ),
     *     ),
     * )
     */
    public function sendEmail(generateEmail $request): \Illuminate\Http\JsonResponse
    {
        $user = User::firstWhere('email',$request->email);
        $email = $user->email;
        if($user){
            $otp_token = str::random(32);
            $code =  rand(1000, 9999);
            $otp = OtpCode::firstWhere('login_method_value',$user->email);
            if($otp){
                $otp->update([
                    'attempt' => $otp->attempt+1,
                    'code'=>$code,
                    'otp_token' => $otp_token,
                ]);
            }else{
                OtpCode::create([
                    'login_method_value' => $user->email,
                    'login_method'=> 'email',
                    'code'=>$code,
                    'otp_token'=>$otp_token,
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
                'message' => "email not found",
            ],  status: 422);
    }

    /**
     * @param EmailCode $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/verifyEmail",
     *     summary="Send verifiction code to server",
     *     description="Send verifiction code to server",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="khanramakisaeideh@gmail.com"),
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
    public function verifyCode(EmailCode $request): \Illuminate\Http\JsonResponse
    {
        $user = User::firstWhere('email',$request->email);
        if($user){
            $code = OtpCode::firstWhere([
                ['login_method_value',$user->email],
                ['login_method', 'email'],
                ['otp_token',$request->otp_token]
            ]);
            if($code){
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
                        'not_found' => 'Email does not match'
                    ],
                ],status:404);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => [
                    'not_found' => 'The email is incorrect'
                ],
            ],status: 404);
        }
    }
}
