<?php

require_once 'vendor/autoload.php';

use App\Eloquent\User;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;

/**
 * Illuminate/paginiation
 * Allows you to add a pagination layer to your illuminate database results.
 *
 * Note: Additional work is still required to use the pagination library indepently
 * from eloquent (illuminate/database). Example coming soon...
 *
 * Requires: illuminate/database
 *           illuminate/pagination
 *
 * @source https://github.com/illuminate/pagination
 * @contributor https://github.com/jamescarlos
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
    // Set up the database connection--see the database component for more info
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
    ]);

    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    // End of database setup

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
        exit("Need more than <strong>$perPage</strong> users in your <i>illuminate_non_laravel</i> database to see this work");
    }

    // Set $page (optional, defaults to null) to the current page;
    // if this is not set, the currentPageResolver will be used
    $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : null;

    // Query and paginate the results
    $results = User::orderBy('id')->paginate($perPage, $columns, $pageName, $page);

    // Display the table of users
    echo '<h1>Users</h1>';
    echo '<table>';
    foreach ($results as $user) {
        echo "<tr><td>User number {$user->id}</td></tr>";
    }
    echo '<table>' . "\n";

    // Render the Bootstrap framework compatible pagination html;
    // the appends() method retains any other query string parameters
    // so that they can be passed along with pagination links
    echo $results->appends($_GET)->render();

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
});

$app->run();
