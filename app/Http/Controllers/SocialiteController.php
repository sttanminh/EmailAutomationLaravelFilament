<?php

namespace App\Http\Controllers;
 
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $response = Socialite::driver($provider)->user();

        // Find or create user
        $user = User::updateOrCreate(
            ['email' => $response->getEmail()],
            [
                'name' => $response->getName(),
                'google_id' => $response->getId(),
                'password' => bcrypt(str()->random(16)), // Generate a random password
            ]
        );

        Auth::login($user);

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }
}
