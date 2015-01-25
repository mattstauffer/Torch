<?php

require_once '../../vendor/autoload.php';

use App\Eloquent\User;
use App\Eloquent\UserEncapsulated;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

/**
 * Illuminate/database, aka Eloquent, can be used via Capsule. See below a plain MVP,
 * and also a class-encapsulated option
 *
 * Requires: illuminate/database
 *
 * @source https://github.com/illuminate/database
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {

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
	echo '<pre>';

	$user = Capsule::table('users')->where('id', 1)->get();

	var_dump($user);

	$users = User::all();

	var_dump($users);

	// More examples and docs here: https://github.com/illuminate/database
});

$app->get('/encapsulated', function() {
	echo '<pre>';

	$users = UserEncapsulated::all();

	var_dump($users);
});
$app->run();
