<?php

use Illuminate\Config\Repository;

require_once 'vendor/autoload.php';

/**
 * Illuminate/config
 *
 * @source https://github.com/illuminate/config
 */

$app = new \Slim\App(['settings' => ['debug' => true]]);
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$configPath = __DIR__ . '/config/';

$app->get('/', function () use ($configPath) {
    // Init
    $config = new Repository(require $configPath . 'app.php');

    // Get config using the get method
    echo "This is coming from config/app.php: <hr>" . $config->get('app.siteName') . "<br><br><br>";

    // Get config using ArrayAccess
    echo "This is coming from config/app.php: <hr>" . $config['app.user'] . "<br><br>";

    // Set a config
    $config->set('settings.greeting', 'Hello there how are you?');

    echo "Set using config->set: <hr>" . $config->get('settings.greeting');
});

$app->run();
