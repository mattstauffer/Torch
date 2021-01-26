<?php

require_once 'vendor/autoload.php';
require_once '../../src/App.php';

use Illuminate\Routing\Router;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Session\SessionManager;

$container = App::getInstance();

$request = Request::capture();

$container->bind(Illuminate\Http\Request::class, function ($app) use ($request) {
    return $request;
});

$container->alias(Illuminate\Http\Request::class, 'request');

$events = new Dispatcher(new Container);

$container->bind(Illuminate\Events\Dispatcher::class, function ($app) use ($events) {
    return $events;
});

$container->alias(Illuminate\Events\Dispatcher::class, 'events');

$container->bind(\Illuminate\Filesystem\Filesystem::class, function ($app) {
    return new \Illuminate\Filesystem\Filesystem;
});

$container->alias(\Illuminate\Filesystem\Filesystem::class, 'files');

$container->singleton('auth', function ($app) {
    // Once the authentication service has actually been requested by the developer
    // we will set a variable in the application indicating such. This helps us
    // know that we need to set any queued cookies in the after event later.
    $app['auth.loaded'] = true;

    return new Illuminate\Auth\AuthManager($app);
});

$container->singleton('auth.driver', function ($app) {
    return $app['auth']->guard();
});

$container->bind(
    AuthenticatableContract::class, function ($app) {
        return call_user_func($app['auth']->userResolver());
    }
);

$container->singleton(GateContract::class, function ($app) {
    return new Gate($app, function () use ($app) {
        return call_user_func($app['auth']->userResolver());
    });
});

$container->bind(
    RequirePassword::class, function ($app) {
        return new RequirePassword(
            $app[ResponseFactory::class],
            $app[UrlGenerator::class],
            $app['config']->get('auth.password_timeout')
        );
    }
);

$container->bind('config', function ($app) {
    return new Repository(require __DIR__ . '/config/app.php');
});

$container->rebinding('request', function ($app, $request) {
    $request->setUserResolver(function ($guard = null) use ($app) {
        return call_user_func($app['auth']->userResolver(), $guard);
    });
});

$container->rebinding('events', function ($app, $dispatcher) {
    if (! $app->resolved('auth')) {
        return;
    }

    if ($app['auth']->hasResolvedGuards() === false) {
        return;
    }

    if (method_exists($guard = $app['auth']->guard(), 'setDispatcher')) {
        $guard->setDispatcher($dispatcher);
    }
});

$container->singleton('hash', function ($app) {
    return new HashManager($app);
});

$container->singleton('hash.driver', function ($app) {
    return $app['hash']->driver();
});

// Now we need to fire up the session manager
$container->singleton('session', function ($app) {
    return new SessionManager($app);
});

$container->singleton('session.store', function ($app) {
    // First, we will create the session manager which is responsible for the
    // creation of the various session drivers when they are needed by the
    // application instance, and will resolve them on a lazy load basis.
    return $app->make('session')->driver();
});

$container->singleton('cookie', function ($app) {
    $config = $app->make('config')->get('session');

    return (new \Illuminate\Cookie\CookieJar)->setDefaultPathAndDomain(
        $config['path'], $config['domain'], $config['secure'], $config['same_site'] ?? null
    );
});

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'illuminate_non_laravel',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
], 'mysql');

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database' => 'database.sqlite',
    'prefix' => '',
]);

$capsule->setEventDispatcher($events);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Create the router instance
$router = new Router($events, $container);

// Global middlewares
$globalMiddleware = [
    \App\Middleware\StartSession::class,
];

// Array middlewares
$routeMiddleware = [
    'auth' => \App\Middleware\Authenticate::class,
    'guest' => \App\Middleware\RedirectIfAuthenticated::class,
];

// Load middlewares to router
foreach ($routeMiddleware as $key => $middleware) {
    $router->aliasMiddleware($key, $middleware);
}

// Load the routes
require_once 'routes.php';

// Create a request from server variables
$request = Request::capture();

// Dispatching the request:
// When it comes to dispatching the request, you have two options:
// a) you either send the request directly through the router
// or b) you pass the request object through a stack of (global) middlewares
// then dispatch it.

// a. Dispatch the request through the router
// $response = $router->dispatch($request);

// b. Pass the request through the global middlewares pipeline then dispatch it through the router
$response = (new Pipeline($container))
    ->send($request)
    ->through($globalMiddleware)
    ->then(function ($request) use ($router) {
        return $router->dispatch($request);
    });

// Send the response back to the browser
$response->send();

// User::create([
//     'email' => 'admin',
//     'name' => 'admin',
//     'password' => $container->get('hash')->make('password'),
// ]);