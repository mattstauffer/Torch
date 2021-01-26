<?php

namespace App\Middleware;

use Closure;

class StartSession
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
        if (session_status() == PHP_SESSION_NONE) {
            // In order to maintain the session between requests, we need to populate the
            // session ID from the supplied cookie
            $cookieName = \App::getInstance()['session']->getName();

            if (isset($_COOKIE[$cookieName])) {
                if ($sessionId = $_COOKIE[$cookieName]) {
                    \App::getInstance()['session']->setId($sessionId);
                }
            }

            // Boot the session
            \App::getInstance()['session']->start();
        }

        return $next($request);
    }
}
