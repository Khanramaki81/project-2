<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class is_admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        $user = auth()->user();
//        if($user){
//            return $next($request);
//        }
//
//        return response()->json([
//            'success' => false,
//            'data' => $user,
//        ]);
        $user = auth()->user();

//        if (!$user) {
//            return response()->json(['message' => 'Unauthorized'], 401);
//        }

        // اگر می خواهید بررسی کنید که کاربر نقش admin دارد:
//        if (!$user->hasRole('admin')) {
//            return response()->json(['message' => 'User is not admin'], 403);
//        }

        return $next($request);
    }
}
