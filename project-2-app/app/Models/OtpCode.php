<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'login',
        'code',
        'attempt',
        'expired_at',
    ];
}
