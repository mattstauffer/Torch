<?php

require_once 'vendor/autoload.php';

use App\Eloquent\User;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

/**
 * Illuminate/paginiation
 * Allows you to add a pagination layer to your arrays or Illuminate database results.
 *
 * Requires: illuminate/pagination
 *           illuminate/database
 *           illuminate/view
 *
 * @source https://github.com/illuminate/pagination
 * @contributor https://github.com/jamescarlos
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

// Create ViewFactory instance -- see the view component for more info
$events = new Dispatcher(new Container);

$pathsToTemplates = [__DIR__ . '/templates'];

$filesystem = new Filesystem;

$viewResolver = new EngineResolver;
$viewResolver->register('php', function () use ($filesystem) {
    return new PhpEngine($filesystem);
});

$viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);

$viewFactory = new Factory($viewResolver, $viewFinder, $events);
// End of create ViewFactory instance

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="database">Database</a> | <a href="array">Non-database</a>');

    return $response;
});

// This route demonstrates an example of using the paginator with the illuminate\database component
$app->get('/database', function (Request $request, Response $response) use ($viewFactory, $events) {
    // Set up the database connection -- see the database component for more info
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'illuminate_non_laravel',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ], 'mysql');

    $capsule->addConnection([
        'driver'    => 'sqlite',
        'database' => 'database.sqlite',
        'prefix' => '',
    ]);

    $capsule->setEventDispatcher($events);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    // End of database setup

    // Set the view factory resolver
    Paginator::viewFactoryResolver(function () use ($viewFactory) {
        return $viewFactory;
    });

    // Set up a current path resolver so the paginator can generate proper links
    Paginator::currentPathResolver(function () {
        return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
    });

    // Set up a current page resolver
    Paginator::currentPageResolver(function ($pageName = 'page') {
        $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : 1;
        return $page;
    });

    $perPage = 5; // results per page
    $columns = ['*']; // (optional, defaults to *) array of columns to retrieve from database
    $pageName = 'page'; // (optional, defaults to 'page') query string parameter name for the page number

    if (User::all()->count() <= $perPage) {
        $response->getBody()->write("Need more than <strong>$perPage</strong> users in your <i>illuminate_non_laravel</i> database to see this work");

        return $response;
    }

    // Set $page (optional, defaults to null) to the current page;
    // if this is not set, the currentPageResolver will be used
    $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : null;

    // Query and paginate the results
    $results = User::orderBy('id')->paginate($perPage, $columns, $pageName, $page);

    // Display the table of users
    $response->getBody()->write('<h1>Users</h1>');
    $response->getBody()->write('<table>');
    foreach ($results as $user) {
        $response->getBody()->write("<tr><td>User number {$user->id}</td></tr>");
    }
    $response->getBody()->write('<table>' . "\n");

    // Render the Bootstrap framework compatible pagination html;
    // the appends() method retains any other query string parameters
    // so that they can be passed along with pagination links
    $response->getBody()->write($results->appends($_GET)->links('pagination')->toHtml());

    // additional helper methods available are:
    // $results->count();
    // $results->currentPage();
    // $results->hasMorePages();
    // $results->lastPage();
    // $results->nextPageUrl();
    // $results->perPage();
    // $results->previousPageUrl();
    // $results->total();
    // $results->url($page);
    // $results->firstItem();
    // $results->lastItem();

    return $response;
});


// Build our fake array to paginate
$items = [];
foreach (range(1, 100) as $i) {
    $items[] = [
        'id' => $i,
        'hash' => md5($i)
    ];
}

// This route demonstrates an example of paginating an array of items
$app->get('/array', function (Request $request, Response $response) use ($items, $viewFactory) {
    // Set up the pagination options
    $total = count($items); // total number of items
    $perPage = 25; // results per page
    $currentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; // current page number
    $offset = ($currentPage - 1) * $perPage;
    $options = [
        'path' => strtok($_SERVER['REQUEST_URI'], '?') // the path to use for the pagination links
    ];

    // The pagination library provides 2 classes: Paginator & LengthAwarePaginator.
    // The Paginator class does not need to know the total number of items in the
    // result set; however, because of this, the class does not have methods for
    // retrieving the index of the last page.

    // The LengthAwarePaginator accepts almost the same arguments as the Paginator;
    // however, it does require a count of the total number of items in the result set.

    // Note: You are responsible for manually "slice"ing the array of results you
    // pass to the paginator

    $useLengthAware = true;

    // Paginator class example
    if (! $useLengthAware) {
        $paginatorItems = array_slice($items, $offset);
        $results = new Paginator($paginatorItems, $perPage, $currentPage, $options);
    }
    // End of Paginator example

    // LengthAwarePaginator class example
    if ($useLengthAware) {
        $lengthAwarePaginatorItems = array_slice($items, $offset, $perPage);
        $results = new LengthAwarePaginator($lengthAwarePaginatorItems, $total, $perPage, $currentPage, $options);
    }
    // End of LengthAwarePaginator example

    // Set the view factory resolver
    Paginator::viewFactoryResolver(function () use ($viewFactory) {
        return $viewFactory;
    });

    // Display a paginated table of our array
    $response->getBody()->write('<h1>I love hashes</h1>');
    $response->getBody()->write('<table>');
    foreach ($results as $result) {
        $response->getBody()->write("
        <tr>
            <td>{$result['id']}</td>
            <td>{$result['hash']}</td>
        </tr>");
    }
    $response->getBody()->write('<table>' . "\n");
    $response->getBody()->write($results->appends($_GET)->links('pagination')->toHtml());

    $response->getBody()->write('Current Page: ' . $results->currentPage());
    $response->getBody()->write('<br>Items Per Page: ' . $results->perPage());

    // The following methods are only available when using the LengthAwarePaginator instance
    if ($useLengthAware) {
        $response->getBody()->write('<br>From ' . $results->firstItem() . ' to ' . $results->lastItem());
        $response->getBody()->write('<br>Total Items: ' . $results->total());
        $response->getBody()->write('<br>Last Page: ' . $results->lastPage());
    }

    return $response;
});

$app->run();
