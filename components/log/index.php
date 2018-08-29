<?php
require_once 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

/**
 * Illuminate/log
 *
 * @source https://github.com/illuminate/log
 */

$app = new \Slim\App();

$app->get('/', function ()
{
    // Create new writer instance with dependencies
    $log = new Illuminate\Log\Writer(new Monolog\Logger('Torch Logger'));

    // Setup log file location
    $log->useFiles('./logs/torch.log');

    // Actual log(s)
    $log->info('Logging an info message');

    $log->error('Logging an error message');

    $log->notice('Logging a notice message');

    echo str_replace("\n", "<br>", file_get_contents('./logs/torch.log'));
});

$app->run();
