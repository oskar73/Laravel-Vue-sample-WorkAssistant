<?php

namespace App\Http\Middleware;

use Closure;

class PasswordCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isPasswordForceUpdateNeed()) {
            return redirect("/account/profile#/password")->with("info", "You need to change password to secure your account.");
        }

        return $next($request);
    }
}
