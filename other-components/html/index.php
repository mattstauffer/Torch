<?php

/**
 * LaravelCollective/html
 *
 * @source https://github.com/LaravelCollective/html
 * @contributor https://github.com/dead23angel
 */

require_once 'vendor/autoload.php';

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\SessionManager;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;

$pathsToTemplates = [__DIR__ . '/templates'];
$pathToCompiledTemplates = __DIR__ . '/compiled';

// Dependencies
$filesystem = new Filesystem;
$eventDispatcher = new Dispatcher(new Container);

// Create View Factory capable of rendering PHP and Blade templates
$viewResolver = new EngineResolver;
$viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);
$viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

$app           = new Container();
$app['events'] = new Dispatcher();
$app['files']  = new Filesystem;
$app['view'] = $viewFactory;
$app['config'] = new Config([
    'session' => [
        'lifetime' => 120,
        'expire_on_close' => false
    ]
]);

$app['config']['session.lottery'] = [2, 100];
$app['config']['session.cookie'] = 'laravel_session';
$app['config']['session.path'] = '/';
$app['config']['session.domain'] = null;
$app['config']['session.driver'] = 'file';
$app['config']['session.files'] = __DIR__ . '/sessions';

$sessionManager = new SessionManager($app);
$app['session.store'] = $sessionManager->driver();
$app['session'] = $sessionManager;

// Create the router instance
$router = new Router($app['events']);

// Create a request from server variables
$request = Request::capture();

// Create UrlGenerator
$url = new UrlGenerator($router->getRoutes(), $request);

// Create HtmlBuilder
$html = new HtmlBuilder($url, $app['view']);

// Create FormBuilder
$form = new FormBuilder($html, $url, $app['view'], $request);

echo $form->open(['url' => 'foo/bar']);
echo $form->select('animal', [
    'Cats' => ['leopard' => 'Leopard'],
    'Dogs' => ['spaniel' => 'Spaniel'],
]);
echo $form->close();
