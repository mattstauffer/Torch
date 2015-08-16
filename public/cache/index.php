<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container; // Only needed for DB
use Illuminate\Events\Dispatcher; // Only needed for DB
use Illuminate\Filesystem\Filesystem;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/cache, using filesystem
 *
 * Requires: illuminate/cache
 *           illuminate/filesystem (for file cache)
 *           illuminate/database (for IOC)
 *
 * @source https://github.com/illuminate/cache
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$cache_dir = __DIR__  . '/../../cache';

$app->get('/', function () use ($cache_dir) {
	// Filesystem cache
	$capsule = new Capsule;
	$container = $capsule->getContainer();

	$container['config']['cache.driver'] = 'file';
	$container['config']['cache.path'] = $cache_dir;
	$container['files'] = new Filesystem();

	$capsule->setCacheManager(new CacheManager($container));

	$cache = $container->make('cache');
	$cache->put('cache-test', 'Howdy. I am teh cache.', 500);

	echo $cache->get('cache-test');
});

$app->get('/cacheDatabase', function() use ($cache_dir) {
	// Filesystem cache, merged with basic database connection for 'remember'
	$capsule = new Capsule;
	$container = $capsule->getContainer();

	$container['config']['cache.driver'] = 'file';
	$container['config']['cache.path'] = $cache_dir;
	$container['files'] = new Filesystem();

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
	$capsule->setCacheManager(new CacheManager($container));

	// Make this Capsule instance available globally via static methods... (optional)
	$capsule->setAsGlobal();

	// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
	$capsule->bootEloquent();

	// Use it
	echo '<pre>';

	$user = Capsule::table('users')->where('id', 1)->remember(10)->get();

	var_dump($user);
});
$app->run();
