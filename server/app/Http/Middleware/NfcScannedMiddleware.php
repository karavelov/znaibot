<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class NfcScannedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        $isLoggedIn = (bool) $request->session()->get('nfc_scanned', false);
        $sessionRole = (string) $request->session()->get('nfc_role', '');
        $sessionUserId = (int) $request->session()->get('user_id', 0);

        if (!$isLoggedIn || $sessionUserId <= 0 || $sessionRole === '') {
            return redirect()->route('scan');
        }

        $user = DB::table('users')
            ->where('id', $sessionUserId)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            $request->session()->forget(['nfc_scanned', 'nfc_card_id', 'nfc_role', 'user_id']);
            return redirect()->route('scan');
        }

        if ($sessionRole !== $user->role) {
            $request->session()->put('nfc_role', $user->role);
            $sessionRole = (string) $user->role;
        }

        if ($role !== null && $sessionRole !== $role) {
            return redirect()->route('scan');
        }

        return $next($request);
    }
}
