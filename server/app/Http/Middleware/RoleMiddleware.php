<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if($request->user()->role != $role) {
            return redirect()->route('dashboard');
        }

        //**  last seen (heavier on DB) */
        // if (Auth::check()) {
        //     $expireTime = Carbon::now()->addMinutes(5);
        //     Cache::put('user-is-online' . Auth::user()->id, true, $expireTime);

        //     User::where('id', Auth::user()->id)->update(
        //         [
        //             'last_login_at' => Carbon::now(),
        //             'last_login_ip' => $request->getClientIp()
        //         ]
        //     );
        // }
        
        return $next($request);
    }
}
