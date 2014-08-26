<?php

// Use the str_finish helper method to assure a trailing slash at the end of path string
$basePath = str_finish(dirname(__FILE__), '/');

$controllersDirectory = $basePath . 'Controllers';
$modelsDirectory = $basePath . 'Models';

// Register Laravel's autoloader
Illuminate\Support\ClassLoader::register();

// Register directories into the autoloader
Illuminate\Support\ClassLoader::addDirectories(array(
	$controllersDirectory,
	$modelsDirectory
));

// Instantiate the container
$app = new Illuminate\Container\Container();
$app->bind('app', $app);
$app['env'] = 'production';

// Register service providers
$providers = array(
	'Illuminate\Events\EventServiceProvider',
	'Illuminate\Routing\RoutingServiceProvider'
);

foreach ($providers as $provider)
{
	with (new $provider($app))->register();
}

// Set application instance on Facade class so the helper methods
// can know about current instance of application
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
