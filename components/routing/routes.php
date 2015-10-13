<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->get('/', function() {
	echo "index page";
});

$router->get('/hello/{name}', function($name) {
	echo "Hello ". $name;
});

$router->group(['prefix' => 'users'], function(Router $router) {

	$router->get('/', ['as' => 'users.index', 'uses' => 'UsersController@index']);

	$router->post('/', ['as' => 'users.store', 'uses' => 'UsersController@store']);

});