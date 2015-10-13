<?php

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

require_once 'vendor/autoload.php';

/**
 * Illuminate/config
 *
 * @source https://github.com/illuminate/config
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
    $container           = new Container;
    $container['config'] = [
        'cache.default'     => 'file',
        'cache.stores.file' => [
            'driver' => 'file',
            'path'   => __DIR__ . '/../../cache'
        ]
    ];
    $container['files']  = new Filesystem;

    $cacheManager = new CacheManager($container);
    $cache        = $cacheManager->store();
    $cache->put('test', 'This is loaded from cache.', 500);

    echo $cache->get('test');
});

$app->run();
