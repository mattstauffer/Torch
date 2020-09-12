<?php

require_once 'vendor/autoload.php';

use App\Eloquent\User;
use App\Eloquent\UserEncapsulated;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

/**
 * Illuminate/database, aka Eloquent, can be used via Capsule. See below a plain MVP,
 * and also a class-encapsulated option
 *
 * Requires: illuminate/database
 *
 * @source https://github.com/illuminate/database
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) {
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

    // Set the event dispatcher used by Eloquent models... (optional)
    $capsule->setEventDispatcher(new Dispatcher(new Container));

    // Set the cache manager instance used by connections... (optional)
    // $capsule->setCacheManager(...);

    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    // Use it
    $response->getBody()->write('<h2>First User using the query builder:</h2>');
    $response->getBody()->write('<pre>');

    $user = Capsule::table('users')->where('id', 1)->get();

    $response->getBody()->write(json_encode($user, JSON_PRETTY_PRINT));
    $response->getBody()->write('</pre>');

    $response->getBody()->write('<h2>All Users using Eloquent:</h2>');
    $response->getBody()->write('<pre>');

    $users = User::all();

    $response->getBody()->write(json_encode($users, JSON_PRETTY_PRINT));

    // More examples and docs here: https://github.com/illuminate/database

    return $response;
});

/**
 * "Encapsulated" Eloquent object pattern; not the normal use case, but here
 * for demonstration of a way to store the connection & its settings from one
 * model to another
 */
$app->get('/encapsulated', function (Request $request, Response $response) {
    $response->getBody()->write('<pre>');

    $users = UserEncapsulated::all();

    $response->getBody()->write('<pre>');
    $response->getBody()->write(json_encode($users, JSON_PRETTY_PRINT));
    $response->getBody()->write('</pre>');

    return $response;
});

$app->run();
