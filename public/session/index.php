<?php

require_once '../../vendor/autoload.php';

/**
* Illuminate/session
*
* Illuminate Sessions outside of laravel;
*
* Requires: illuminate/support
*           illuminate/container
*           illuminate/session
*           illuminate/config
*
* @todo Drivers other than the file driver
*
* @source https://github.com/illuminate/session
* @author Sam Jordan
*/

// Determine our base path
$path = __DIR__;

// Init the container
$app = new Illuminate\Container\Container();
$app->bind('app', $app);

$app['config'] = new Illuminate\Config\Repository(
    new Illuminate\Config\FileLoader(
        new Illuminate\Filesystem\Filesystem,
        $path
    ),
    'production'
);
$app['files'] = new Illuminate\Filesystem\Filesystem;

// Not 100% sure on how many of these are needed
$app['config']['session.lifetime'] = 120; // Minutes idleable
$app['config']['session.expire_on_close'] = false; // Minutes idleable
$app['config']['session.lottery'] = array(2, 100); // lottery--how often do they sweep storage location to clear old ones?
$app['config']['session.cookie'] = 'laravel_session';
$app['config']['session.path'] = '/';
$app['config']['session.domain'] = null;
$app['config']['session.driver'] = 'file';
$app['config']['session.files'] = $path . '/sessions';

// Cookie time
$app['cookie'] = (new Illuminate\Cookie\CookieJar)->setDefaultPathAndDomain('/', null);

// Now we need to fire up the session manager
$sessionManager = new Illuminate\Session\SessionManager($app);
$app['session.store'] = $sessionManager->driver();
$app['session'] = $sessionManager;

// In order to maintain the session between requests, we need to populate the
// session ID from the supplied cookie
$cookieName = $app['session']->getName();

if (isset($_COOKIE[$cookieName])) {
    if ($sessionId = $_COOKIE[$cookieName]) {
        $app['session']->setId($sessionId);
    }
}

// Boot the session
$app['session']->start();

// Set a variable if it isn't already set
if (!$app['session']->has('test')) {
    echo "<p>'test' is not set, adding it to the session via put.</p>";
    $app['session']->put('test', 'laravel');
} else {
    echo "<p>'test' exists on the session.</p>";
}

// Retrieve it
echo "<hr /><pre>";
var_dump($app['session']->all());

// Save the session
$app['session']->save();

// Now the session is saved, we'll store the session ID in a cookie to allow for
// the session to remain on future requests
$cookie = new Symfony\Component\HttpFoundation\Cookie(
    $app['session']->getName(),
    $app['session']->getId(),
    time() + ($app['config']['session.lifetime'] * 60),
    '/', null, false
);
setcookie(
    $cookie->getName(),
    $cookie->getValue(),
    $cookie->getExpiresTime(),
    $cookie->getPath(),
    $cookie->getDomain()
);
