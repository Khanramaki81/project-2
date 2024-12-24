<?php

namespace App\Providers;

use App\Events\SmsVerifiCode;
use App\Listeners\SmsVerifiCodeListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
//        Event::listen(
//            SmsVerifiCode::class,
//            SmsVerifiCodeListener::class,
//        );
        Password::defaults(function(){
            return Password::min(8)
                ->mixedCase();
//                ->uncompromised();
        });
    }
}
