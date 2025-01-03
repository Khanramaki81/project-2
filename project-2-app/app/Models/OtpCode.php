<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'login_method_value',
        'login_method',
        'code',
        'otp_token',
        'attempt',
        'expired_at',
    ];
}
