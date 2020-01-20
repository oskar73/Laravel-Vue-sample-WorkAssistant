<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Socialite;

class SocialController extends Controller
{
    public $socials;

    public function __construct()
    {
        $this->socials = [
            'facebook',
            'linkedin',
            'twitter',
            'google',
            'instagram',
            'github',
        ];
    }

    public function redirectToProvider($provider)
    {
        try {
            if (! in_array($provider, $this->socials)) {
                return redirect()->route('login')->with('error', 'Provider is invalid.');
            }

            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong.');
        }
    }

    public function handleProviderCallback($provider)
    {
        if (! in_array($provider, $this->socials)) {
            return redirect()->route('login')->with('error', 'Provider is invalid.');
        }

        try {
            $socialuser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong.');
        }

        $authUser = User::where('provider', $provider)
            ->where('provider_id', $socialuser->id)
            ->first();

        if ($authUser == null) {
            $check = User::where("email", $socialuser->email)->first();
            if ($check) {
                return redirect()->route('login')->with('info', 'Your social account email already exists in our website. To secure your account, please login with your email.');
            }
            $authUser = User::create([
                'name' => $socialuser->name,
                'username' => uniqid(),
                'email_verified_at' => now()->toDateTimeString(),
                'email' => $socialuser->email,
                'provider' => $provider,
                'provider_id' => $socialuser->id,
            ]);
            $authUser->addMediaFromUrl($socialuser->avatar)
                ->usingFileName(uniqid() . ".jpg")
                ->toMediaCollection('avatar');
        }

        $authUser->addSubscriber(1);

        Auth::login($authUser, true);

        return redirect()->route('dashboard');
    }
}
