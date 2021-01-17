<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require_once 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

/**
 * Illuminate/log
 *
 * @source https://github.com/illuminate/log
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) {
    // Create new writer instance with dependencies
    $log = new Illuminate\Log\Logger(new Monolog\Logger('Torch Logger'));

    // Setup log file location
    $log->pushHandler(new Monolog\Handler\StreamHandler('./logs/torch.log'));

    // Actual log(s)
    $log->info('Logging an info message');

    $log->error('Logging an error message');

    $log->notice('Logging a notice message');

    $response->getBody()->write(str_replace(PHP_EOL, '<br>', file_get_contents('./logs/torch.log')));

    return $response;
});

$app->run();
