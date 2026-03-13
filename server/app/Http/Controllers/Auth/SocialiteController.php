<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;

class SocialiteController extends Controller
{
    public function loginSocial(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($request);
 
        return Socialite::driver($provider)->redirect();
    }
 
    public function callbackSocial(Request $request, string $provider)
    {
        $this->validateProvider($request);
 
        $response = Socialite::driver($provider)->user();
 
        $user = User::firstOrCreate(
            ['email' => $response->getEmail()],
            [
                'password' => Str::random(16),
                // 'role' => 'student',
                'email_verified_at' => Carbon::now(),
            ]
        );
        
        $data = [$provider . '_id' => $response->getId()];
 
        if ($user->wasRecentlyCreated) {
            $data['name'] = $response->getName() ?? $response->getNickname();
 
            event(new Registered($user));
        }
 
        $user->update(array_merge($data, ['email_verified_at' => Carbon::now()]));
 
        Auth::login($user, remember: true);
 
        return redirect()->intended(RouteServiceProvider::HOME);
    }
 
    protected function validateProvider(Request $request): array
    {
        return $this->getValidationFactory()->make(
            $request->route()->parameters(),
            ['provider' => 'in:facebook,google,github']
        )->validate();
    }
}
