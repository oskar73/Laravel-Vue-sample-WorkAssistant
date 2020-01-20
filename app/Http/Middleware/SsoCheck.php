<?php

namespace App\Http\Middleware;

use App\Exceptions\Sso;
use Closure;

class SsoCheck
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
        Sso::check();

        return $next($request);
    }
}
