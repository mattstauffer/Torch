<?php

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
 */

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/

// Include project libraries, including a number of mock
// examples of common, real-world services
require_once '../../vendor/autoload.php';
require_once 'libraries/Authentication.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';
require_once 'libraries/Mailer.php';
require_once 'libraries/Template.php';

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


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$app = new \Slim\Slim();

$app->get('/', function () use ($container) {

	// Create new Acme\Template instance
	$template = $container->make('template');

	// Render template
	echo $template->render('home');
});

$app->get('/send-email', function () use ($container) {

	// Create new Acme\Mailer instance
	$mailer = $container->make('mailer');

	// Set mail settings
	$mailer->to = 'foo@bar.com';
	$mailer->subject = 'Test email';
	$mailer->body = 'This is a test email.';

	// Send the email
	if ($mailer->send()) {
		echo 'Email successfully sent!';
	}
});

$app->get('/login', function () use ($container) {

	// Create new Acme\Authentication instance
	$auth = $container->make('auth');

	// Validate the user credentials
	if ($auth->verifyLogin('username', 'password')) {
		echo 'User successfully logged in!';
	}
});

$app->get('/articles', function () use ($container) {

	// Create new Acme\Database instance
	$database = $container->make('database');

	// Select all articles from the database
	$articles = $database->select('SELECT * FROM articles ORDER BY title');

	// Display the articles
	foreach ($articles as $article) {
		echo '<a href="#">' . $article['title'] . '</a><br>';
	}
});

// Example of automatic resolution, where the container automatically
// creates an instance of the requested controller, including all
// of its class dependencies
$app->get('/automatic-resolution', array($container->make('Acme\Controller'), 'home'));

$app->run();
