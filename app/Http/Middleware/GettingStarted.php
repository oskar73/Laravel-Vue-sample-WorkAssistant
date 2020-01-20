<?php

namespace App\Http\Middleware;

use Closure;

class GettingStarted
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
            return redirect("/account/getting-started");
        }

        return $next($request);
    }
}
