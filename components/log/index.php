<?php
require_once 'vendor/autoload.php';

    /*
     * Illuminate/log
     *
     * @source https://github.com/illuminate/log
     */

    // create new Slim instance
    $app = new \Slim\Slim();

    //attach a route
    $app->get('/', function () {
        //create new writer instance with dependencies
        $log = new Illuminate\Log\Writer(new Monolog\Logger('Torch Logger'));

        //setup log file location
        $log->useFiles('./logs/torch.log');

        //actual logging(s)
        $log->info('Logging INFO message');
        echo '<p style="color:#0000FF">Logging INFO message</p>';

        $log->error('Logging ERROR message');
        echo '<p style="color:#FF0000">Logging ERROR message</p>';

        $log->notice('Logging NOTICE message');
        echo '<p style="color:#008000">Logging NOTICE message</p>';

    });
    $app->run();
