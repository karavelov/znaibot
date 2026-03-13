<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();


        /** Check user status */
        if(Auth::user()->status == 'inactive'){
            Auth::guard('web')->logout();
            $request->session()->regenerateToken();

            toastr('Вашият достъп е ограничен. Моля свържете се с администратора.', 'error', 'Account Banned');
            return redirect()->route('home');
        }



        /** Check user role */
        if (Auth::user()->role == 'admin') {

            return redirect()->route('admin.dashboard');
        } else if (Auth::user()->role == 'vendor') {
            return redirect()->route('vendor.dashboard');
        } else {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = Auth::user();


        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        if ($user && $user->role == 'admin') {
            return redirect()->route('admin.login');
        } else if ($user && $user->role == 'vendor') {
            return redirect()->route('login');
        }

        return redirect('/');
    }
}
