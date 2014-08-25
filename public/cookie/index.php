<?php

use Illuminate\Cookie\CookieJar;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/cookie
 *
 * Requires: illuminate/cookie
 *
 * @source https://github.com/illuminate/cookie
 */

date_default_timezone_set('UTC');

$default_session_path = '/';
$default_session_domain = null;

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () use($default_session_path, $default_session_domain) {

	echo '<pre>';

	$jar = (new CookieJar)->setDefaultPathAndDomain($default_session_path, $default_session_domain);

	// Get isn't on Jar.
//	echo 'Old: ' . $jar->get('testing');

	$jar->queue('testing', date('c'), 15);

	echo 'New: ' . date('c');

	// Outbound
	foreach ($jar->getQueuedCookies() as $cookie)
	{
		// This would require using Symfony response objects, wouldn't it? From the code:
		// $response = $this->app->handle($request, $type, $catch);
		// * @return \Symfony\Component\HttpFoundation\Response
		// Is it enough to grab this Cookie and set it manually? Sounds completely hacky.
		// But requiring a symfony response object sounds way off, too.
		dd($cookie);
		$response->headers->setCookie($cookie);
	}
});

$app->run();
