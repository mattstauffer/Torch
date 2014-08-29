<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Session\SessionManager;
use Illuminate\Filesystem\Filesystem; // For file driver

require_once '../../vendor/autoload.php';

/**
 * Illuminate/session
 *
 * Requires: illuminate/session
 *           illuminate/database
 *
 * @source https://github.com/illuminate/session
 * @todo Add examples for other drivers
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
	$capsule = new Capsule;
	$container = $capsule->getContainer();

	// Maybe we need a cache manager???
	$capsule->setCacheManager(new \Illuminate\Cache\CacheManager($container));

	// Overkill config
//	$container['config']['session.driver'] = 'native';
	$container['config']['session.lifetime'] = 1440; // Minutes idleable
	$container['config']['session.lottery'] = array(2, 100); // lottery--how often do they sweep storage location to clear old ones?
	$container['config']['session.cookie'] = 'my_cookie_name_here';
	$container['config']['session.path'] = '/';
	$container['config']['session.domain'] = null;

	$container['config']['session.driver'] = 'file';
	$container['config']['session.files'] = __DIR__ . '/../../cache/sessions';
	$container['files'] = new Filesystem();

	$sessionManager = new SessionManager($container);
	$container['session.store'] = $sessionManager->driver();
	$container['session'] = $sessionManager;

	// what the heck.
//	$serviceProvider = new \Illuminate\Session\SessionServiceProvider($container);
//	$serviceProvider->register();

	$driver = $container->make('session.store');
	$driver->start();
	// crap.. we need an offloader.. and a cookie updater.. :/

	$session = $container->make('session');

	echo '<pre>';

	var_dump($session->all());

	$value = $session->get('test-increment', 0);

	echo "\n$value \n\n";

	$value++;

	$session->put('test-increment', $value);

	var_dump($session->all());

	$driver->save();

	// @todo: Get illuminate http component, attach cookie to it, etc.
	// @todo: $response->send();
});

$app->run();
