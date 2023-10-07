<?php

namespace App\Http\Middleware;
use App\Models\Reader;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
class CanReadComments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $reader = Reader::where('token',$token)->first();

        if (!$reader) {
            // Nếu chưa thì chuyển hướng họ đến trang đăng nhập
            return redirect()->route('reader.login');
        }

        // Nếu đã đăng nhập thì tiếp tục xử lý request
        return $next($request);
    }
}
