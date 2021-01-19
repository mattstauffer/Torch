<?php

require_once 'vendor/autoload.php';
date_default_timezone_set('UTC');

/**
 * Illuminate/filesystem
 *
 * @source https://github.com/illuminate/filesystem
 */

$files = new \Illuminate\Filesystem\Filesystem();

if ($files->exists('readme.md')) {
    echo "✅ README exists".PHP_EOL;
} else {
    echo "🧨 README is missing".PHP_EOL;
}

$container = new \Illuminate\Container\Container;
$container->instance('app', $container);
$container['config'] = new \Illuminate\Config\Repository(require __DIR__ . '/config.php');

$manager = new \Illuminate\Filesystem\FilesystemManager($container);
$disk = $manager->disk('local');

$disk->put(date('Ymd_His').'.txt', bin2hex(random_bytes(64)));

echo json_encode($manager->disk('local')->allFiles('/')).PHP_EOL;
