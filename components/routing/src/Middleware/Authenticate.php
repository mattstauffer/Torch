<?php

namespace Torch\Routing\Middleware;

use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! @$_SESSION['logged_in']) {
            return 'Authentication Error: this URI is for logged-in users only. Please <a href="/login">log in</a>';
        }

        return $next($request);
    }
}
