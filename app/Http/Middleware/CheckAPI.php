<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {
            // Chuyển hướng đến trang đăng nhập hoặc trả về lỗi
            return response()->json(['message' => 'Unautgsgsghorized'], 401);
        }

        return $next($request);
    }
}
