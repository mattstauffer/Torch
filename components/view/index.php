<?php

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

/**
 * Illuminate/view
 *
 * Requires: illuminate/filesystem
 *
 * @source https://github.com/illuminate/view
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
    // Configuration
    // Note that you can set several directories where your templates are located
    $pathsToTemplates = [__DIR__ . '/templates'];
    $pathToCompiledTemplates = __DIR__ . '/compiled';

    // Dependencies
    $filesystem = new Filesystem;
    $eventDispatcher = new Dispatcher(new Container);

    // Create View Factory capable of rendering PHP and Blade templates
    $viewResolver = new EngineResolver;
    $bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);

    $viewResolver->register('blade', function () use ($bladeCompiler, $filesystem) {
        return new CompilerEngine($bladeCompiler, $filesystem);
    });

    $viewResolver->register('php', function () {
        return new PhpEngine;
    });

    $viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);
    $viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

    // Render template with page.blade.php
    $templateData = [
        'title' => 'Title',
        'text' => 'This is my text!',
    ];

    echo $viewFactory->make('page', $templateData)->render();
});

$app->run();
