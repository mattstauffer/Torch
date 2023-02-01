<?php

namespace App\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Cookie;

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
        $container = \App::getInstance();

        if (session_status() == PHP_SESSION_NONE) {
            // In order to maintain the session between requests, we need to populate the
            // session ID from the supplied cookie
            $cookieName = $container['session']->getName();

            if (isset($_COOKIE[$cookieName])) {
                if ($sessionId = $_COOKIE[$cookieName]) {
                    $container['session']->setId($sessionId);
                }
            }

            // Boot the session
            $container['session']->start();
        }

        $response = $next($request);

        $response->headers->setCookie(new Cookie(
            $container['session']->getName(), $container['session']->getId(), 
        ));

        $container['session']->save();

        return $response;
    }
}
