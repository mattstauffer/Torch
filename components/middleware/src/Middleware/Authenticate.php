<?php

namespace App\Middleware;

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
        if (! $_SESSION['user']) {
            return 'Error Authenticate. Please <a href="/login">login</a>';
        }

        return $next($request);
    }
}
