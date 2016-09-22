<?php

/**
 * LaravelCollective/html
 *
 * @source https://github.com/LaravelCollective/html
 * @contributor https://github.com/dead23angel
 */

require_once 'vendor/autoload.php';

use Collective\Html\HtmlBuilder;
use Collective\Html\FormBuilder;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\SessionManager;

$app           = new Container();
$app['events'] = new Dispatcher();
$app['config'] = new Config([
    'session' => [
        'lifetime' => 120,
        'expire_on_close' => false
    ]
]);
$app['files']  = new Filesystem;

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
$html = new HtmlBuilder($url);

// Create FormBuilder
$form = new FormBuilder($html, $url, $app['session.store']);

echo $form->open(['url' => 'foo/bar']);
echo $form->select('animal',[
    'Cats' => ['leopard' => 'Leopard'],
    'Dogs' => ['spaniel' => 'Spaniel'],
]);
echo $form->close();
