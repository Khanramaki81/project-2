<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class not_block
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user && (($user->is_blocked) == 'true')){
            return response()->json([
                'success' => false,
                'message' => [
                    'message' => 'You are blocked.',
                ],
            ],status: 403);
        }
        return $next($request);
    }
}
