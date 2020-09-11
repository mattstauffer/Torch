<?php

use Acme\Mailer;
use Acme\Controller;
use Illuminate\Container\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

/**
 * Illuminate/Container is a powerful inversion of control container,
 * which can easily be used independent of Laravel to help manage
 * class dependencies. In addition to basic class binding, it
 * also supports automatic resolution, which resolves classes
 * without any configuration required.
 *
 * Requires: illuminate/container
 *
 * @source https://github.com/illuminate/container
 * @contributor https://github.com/reinink
 */

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/

// Include project libraries, including a number of mock
// examples of common, real-world services
require_once 'vendor/autoload.php';

// Create new IoC Container instance
$container = new Illuminate\Container\Container;

// Bind a "template" class to the container
$container->bind('template', 'Acme\Template');

// Bind a "mailer" class to the container
// Use a callback to set additional settings
$container->bind('mailer', function ($container) {
    $mailer = new Acme\Mailer;
    $mailer->username = 'username';
    $mailer->password = 'password';
    $mailer->from = 'foo@bar.com';

    return $mailer;
});

// Bind a shared "database" class to the container
// Use a callback to set additional settings
$container->singleton('database', function ($container) {
    return new Acme\Database('username', 'password', 'host', 'database');
});

// Bind an existing "authentication" class instance to the container
$auth = new Acme\Authentication;
$container->instance('auth', $auth);

// Bind an interface to a given implementation.
$container->bind('Acme\Contracts\NotifyUser', 'Acme\TextMessageNotification');

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) use ($container) {
    // Create new Acme\Template instance
    $template = $container->make('template');

    // Render template
    $response->getBody()->write($template->render('home'));

    return $response;
});

$app->get('/send-email', function (Request $request, Response $response) use ($container) {
    // Create new Acme\Mailer instance
    $mailer = $container->make('mailer');

    // Set mail settings
    $mailer->to = 'foo@bar.com';
    $mailer->subject = 'Test email';
    $mailer->body = 'This is a test email.';

    // Send the email
    if ($mailer->send()) {
        $response->getBody()->write('Email successfully sent!');
    }

    return $response;
});

$app->get('/login', function (Request $request, Response $response) use ($container) {
    // Create new Acme\Authentication instance
    $auth = $container->make('auth');

    // Validate the user credentials
    if ($auth->verifyLogin('username', 'password')) {
        $response->getBody()->write('User successfully logged in!');
    }

    return $response;
});

$app->get('/articles', function (Request $request, Response $response) use ($container) {
    // Create new Acme\Database instance
    $database = $container->make('database');

    // Select all articles from the database
    $articles = $database->select('SELECT * FROM articles ORDER BY title');

    // Display the articles
    foreach ($articles as $article) {
        $response->getBody()->write('<a href="#">' . $article['title'] . '</a><br>');
    }

    return $response;
});

// Example of automatic resolution, where the container automatically
// creates an instance of the requested controller, including all
// of its class dependencies
$app->get('/automatic-resolution', [$container->make('Acme\Controller'), 'home']);

// A NotifyUser interface is bound in the container.
// Whenever an implementation is needed
// Illuminate/Container resolves
// the concrete implemention.
$app->get('/interface-to-implementation', function (Request $request, Response $response) use ($container) {
    $notification = $container->make('Acme\Contracts\NotifyUser');
    $notification->sendNotification('Somebody hit the url!');

    return $response;
});

$app->run();
