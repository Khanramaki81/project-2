<?php
namespace app\Helpers;
class SmsHelper{
    public static function sendSms(string $mobile_number, string $message) : bool
    {
//        $username = config('sms.username');
//        $password = config('sms.password');
        $username = env('SMS_RAYEGAN_USERNAME');
        $password = env('SMS_RAYEGAN_PASSWORD');
        $msg = urlencode($message);
        $url = "https://raygansms.com/SendMessageWithCode.ashx?Username=" . $username . "&Password=" . $password . "&Mobile=" . $mobile_number . "&Message=" . $msg;
        $content = @file_get_contents($url);
        return (bool) $content == true;
    }
}
