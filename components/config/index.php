<?php

require_once 'vendor/autoload.php';

use Illuminate\Config\Repository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

/**
 * Illuminate/config
 *
 * @source https://github.com/illuminate/config
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$configPath = __DIR__ . '/config/';

$app->get('/', function (Request $request, Response $response) use ($configPath) {
    // Init
    $config = new Repository(require $configPath . 'app.php');

    // Get config using the get method
    $response->getBody()->write("This is coming from config/app.php: <hr>" . $config->get('app.siteName') . "<br><br><br>");

    // Get config using ArrayAccess
    $response->getBody()->write("This is coming from config/app.php: <hr>" . $config['app.user'] . "<br><br>");

    // Set a config
    $config->set('settings.greeting', 'Hello there how are you?');
    $response->getBody()->write("Set using config->set: <hr>" . $config->get('settings.greeting'));

    return $response;
});

$app->run();
