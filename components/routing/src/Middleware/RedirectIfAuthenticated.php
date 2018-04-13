<?php

namespace Torch\Routing\Middleware;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        if (! empty($_SESSION['logged_in'])) {
            return 'Authentication Error: This URI is for logged-out users only. Please <a href="/logout">log out</a>.';
        }

        return $next($request);
    }
}
