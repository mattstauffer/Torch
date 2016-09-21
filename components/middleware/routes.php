<?php

use Illuminate\Routing\Router;

/** @var $router Router */
$router->group(['middleware' => 'guest'], function (Router $router) {
    $router->get('/login', function () {
        $_SESSION['user'] = true;

        return 'Success auth! <a href="/">Return home</a>';
    });
});

$router->group(['middleware' => 'auth'], function (Router $router) {
    $router->get('/', function () {
        return 'hello world!';
    });

    $router->get('bye', function () {
        return 'goodbye world!';
    });

    $router->get('/logout', function () {
        unset($_SESSION['user']);

        return 'Success logout! <a href="/">Return home</a>';
    });

    $router->group(['namespace' => 'App\Controllers', 'prefix' => 'users'], function (Router $router) {
        $router->get('/', ['name' => 'users.index', 'uses' => 'UsersController@index']);
        $router->post('/', ['name' => 'users.store', 'uses' => 'UsersController@store']);
    });
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
