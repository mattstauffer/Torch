<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {

    // Configuration
    // By the way, you can set several directories where your templates are located
    $pathsToTemplates = [__DIR__.'/templates'];
    $pathToCompiledTemplates = __DIR__.'/compiled';

    // Dependencies
    $filesystem = new Illuminate\Filesystem\Filesystem();
    $eventDispatcher = new Illuminate\Events\Dispatcher($container);

    // Create View Factory capable of rendering PHP and Blade templates
    $viewResolver = new Illuminate\View\Engines\EngineResolver;
    $bladeCompiler = new Illuminate\View\Compilers\BladeCompiler($filesystem, $pathToCompiledTemplates);
    $viewResolver->register('blade', function () use ($bladeCompiler, $filesystem) {
        return new Illuminate\View\Engines\CompilerEngine($bladeCompiler, $filesystem);
    });
    $viewResolver->register('php', function () {
        return new Illuminate\View\Engines\PhpEngine;
    });
    $viewFinder = new Illuminate\View\FileViewFinder($filesystem, $pathsToTemplates);
    $viewFactory = new Illuminate\View\Factory($viewResolver, $viewFinder, $eventDispatcher);

    // Render template
    $templateData = [
        'title' => 'Title',
        'text' => 'This is my text!',
    ];
    echo $viewFactory->make('page', $templateData)->render();

});

$app->run();
