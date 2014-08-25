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
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

/* Config path */
$app->configPath = __DIR__ . '/config';

$app->container->singleton('files', function () {
    return new Filesystem;
});

$app->container->singleton('loader', function ($app) {
    return new FileLoader($app->files, $app->configPath);
});

$app->container->singleton('config', function ($app) {
    return new Repository($app->loader, ENVIRONMENT);
});

$app->get('/', function () use ($app)
{
    // Get config using the get method
    echo "This is coming from config/app.php: <hr>" . $app->config->get('app.siteName') . "<br><br><br>";

    // Get config using ArrayAccess
    echo "This is coming from config/local/settings.php: <hr>" . $app->config['settings.user'] . "<br><br>";

    // Set a config
    $app->config->set('settings.greeting', 'Hello there how are you?');

    echo "Set using config->set: <hr>" . $app->config->get('settings.greeting');
});

$app->run();