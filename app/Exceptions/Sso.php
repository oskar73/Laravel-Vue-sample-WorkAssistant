<?php

namespace App\Exceptions;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class Sso
{
    protected const IDENTIFIER = 'email';

    public $isLoggedIn = false;

    public function getCookieKey()
    {
        return config('sso.cookie', 'SSO_COOKIE');
    }

    private function getCookieDomain()
    {
        return config("sso.domain", 'bizinabox.localhost');
    }

    public static function setCookie()
    {
        $sso = new self();

        $cookie_value = $sso->generateCookieValue();
        $num_of_minutes_until_expire = $sso->getExpireTime();
        Cookie::queue($sso->getCookieKey(), $cookie_value, $num_of_minutes_until_expire, null, $sso->getCookieDomain());
    }

    private function removeCookie()
    {
        Cookie::queue($this->getCookieKey(), null, 0, null, $this->getCookieDomain());
    }

    private function getIdentifier()
    {
        return auth()->user()[self::IDENTIFIER];
    }

    private function generateCookieValue()
    {
        return  base64_encode($this->getIdentifier().$this->makePassword());
    }

    public function makePassword()
    {
        return config("sso.password", 'SSO-PASSWORD');
    }

    private function getExpireTime()
    {
        return (float) config('sso.expire_time', 60 * 24 * 7);
    }

    public function getCookie()
    {
        return Cookie::get($this->getCookieKey());
    }

    public function login()
    {
        $data = base64_decode($this->getCookie());
        $user = User::where("email", str_replace(config("sso.password"), '', $data))->first();
        if ($user) {
            auth()->login($user);
        } else {
            auth()->logout();
        }
    }

    public static function logOut()
    {
        $sso = new self();
        $sso->removeCookie();
        auth()->logout();
    }

    public static function isLoggedIn(): bool
    {
        $sso = new self();
        $sso_cookie = $sso->getCookie();

        return $sso_cookie != null;
    }

    public static function check()
    {
        $sso = new self();

        $sso_cookie = $sso->getCookie();
        if ($sso_cookie == null) {
            $sso::logOut();
        } elseif (! auth()->check() && $sso_cookie) {
            if (is_string($sso_cookie)) {
                $sso->isLoggedIn = true;
                $sso->login();
            }
        }
    }
}
