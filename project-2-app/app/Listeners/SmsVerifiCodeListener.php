<?php

namespace App\Listeners;

use App\Events\SmsVerifiCode;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SmsVerifiCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SmsVerifiCode $event)
    {
        $username = env('SMS_RAYEGAN_USERNAME');
        $password = env('SMS_RAYEGAN_PASSWORD');
//        $mobile_number = $event->phone;
//        $user = User::where('phone', $request->input('phone'))->first();
//        $mobile_number=$user->phone;
//        $value = rand(1000, 9999);
        return $event->phone;
//        $msg = $event->code;
//        $event->code;
//        $msg = urlencode('code: '.$msg);
//        return $msg;
//        $url = "https://raygansms.com/SendMessageWithCode.ashx?Username=" . $username . "&Password=" . $password . "&Mobile=" . $mobile_number . "&Message=" . $msg;
//        $content = @file_get_contents($url);
//        return (bool) $content == true;
    }
}
