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
    // Create a new Container object, needed by the cache manager.
    $container           = new Container;
    // The CacheManager creates the cache "repository" based on config values
    // which are loaded from the config class in the container.
    // More about the config class can be found in the config component, for now we will use an array
    $container['config'] = [
        'cache.default'     => 'file',
        'cache.stores.file' => [
            'driver' => 'file',
            'path'   => __DIR__ . '/../../cache'
        ]
    ];
    // To use the file cache driver we need an instance of Illuminate's Filesystem, also stored in the container
    $container['files']  = new Filesystem;
    // Create the CacheManager
    $cacheManager = new CacheManager($container);
    // Get the default cache driver (file in this case)
    $cache        = $cacheManager->store();
    // The following would work as well, especially if you have multiple drivers configured
    //$cache        = $cacheManager->store('file');
    // Store or "put" a value into cache for 500 minutes
    // You can find the cache files within the root directory
    $cache->put('test', 'This is loaded from cache.', 500);

    // Echo out the value we just stored in cache
    echo $cache->get('test');
});

$app->run();
