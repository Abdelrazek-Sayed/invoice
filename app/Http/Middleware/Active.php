<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Active
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            if (Auth::User()->status == 0) {
                Auth::logout();
                return redirect()->route('login')->with(['error' => 'تم ايقاف حسابك من فضلك اتصل بالقسم المختص']);
            }
        }
        return $next($request);
    }
}
