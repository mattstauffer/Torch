<?php
use Illuminate\Config\FileLoader;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

define('ENVIRONMENT', isset($_SERVER['MY_ENV']) ? $_SERVER['MY_ENV'] : 'local');

require_once '../../vendor/autoload.php';

/**
 * Illuminate/config
 *
 * Requires: illuminate/filesystem (for file accessing the filesystem)
 *           illuminate/support (for array dot notation)
 *
 * @source https://github.com/illuminate/config
 * @see http://thoughts.silentworks.co.uk/slim-php-101-using-laravel-config-package/
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

/* Config path */
$configPath = __DIR__ . '/config';


$app->get('/', function () use ($configPath)
{
	// Init
	$files = new Filesystem;

	$loader = new FileLoader($files, $configPath);

	$config = new Repository($loader, ENVIRONMENT);

    // Get config using the get method
    echo "This is coming from config/app.php: <hr>" . $config->get('app.siteName') . "<br><br><br>";

    // Get config using ArrayAccess
    echo "This is coming from config/local/settings.php: <hr>" . $config['settings.user'] . "<br><br>";

    // Set a config
    $config->set('settings.greeting', 'Hello there how are you?');

    echo "Set using config->set: <hr>" . $config->get('settings.greeting');
});

$app->run();
