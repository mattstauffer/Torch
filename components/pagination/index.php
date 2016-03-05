<?php

require_once 'vendor/autoload.php';

use App\Eloquent\User;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Illuminate/paginiation
 * Allows you to add a pagination layer to your arrays or illuminate database results.
 *
 * Requires: illuminate/pagination
 *           illuminate/database
 *
 * @source https://github.com/illuminate/pagination
 * @contributor https://github.com/jamescarlos
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

// This route demonstrates an example of using the paginator with the illuminate\database component
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

// This route demonstrates an example of paginating an array of items
$app->get('/array', function () {
    // Set up the pagination options
    $total = 100; // total nubmer of items
    $perPage = 25; // results per page
    $currentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; // current page number
    $offset = ($currentPage - 1) * $perPage;
    $options = [
        'path' => strtok($_SERVER['REQUEST_URI'], '?') // the path to use for the pagination links
    ];

    // Build an array of items for the current page
    $items = [];
    for ($i = 1; $i <= $total; $i++) {
        // get the current line item
        $items[] = [
            'id' => $i,
            'hash' => md5($i)
        ];
    }

    // The pagination library provides 2 classes: Paginator & LengthAwarePaginator
    // The Paginator class does not need to know the total number of items in the result set; however, because of this,
    // the class does not have methods for retrieving the index of the last page.
    // The LengthAwarePaginator accepts almost the same arguments as the Paginator; however, it does require a count of
    // the total number of items in the result set.

    // You should manually "slice" the array of results you pass to the paginator

    // Paginator class example
    $paginatorItems = array_slice($items, $offset);
    $results = new Paginator($paginatorItems, $perPage, $currentPage, $options);
    // End of Paginator example

    // LengthAwarePaginator class example
    //$lengthAwarePaginatorItems = array_slice($items, $offset, $perPage);
    //$results = new LengthAwarePaginator($lengthAwarePaginatorItems, $total, $perPage, $currentPage, $options);
    // End of LengthAwarePaginator example

    // Display a paginated table of our array
    echo '<h1>I love hashes</h1>';
    echo '<table>';
    foreach ($results as $result) {
        echo "
        <tr>
            <td>{$result['id']}</td>
            <td>{$result['hash']}</td>
        </tr>";
    }
    echo '<table>' . "\n";
    echo $results->appends($_GET)->render();

    echo 'Current Page: ' . $results->currentPage();
    echo '<br>Items Per Page: ' . $results->perPage();

    // The following methods are only available when using the LengthAwarePaginator instance
    //echo '<br>From ' . $results->firstItem() . ' to ' . $results->lastItem();
    //echo '<br>Total Items: ' . $results->total();
    //echo '<br>Last Page: ' . $results->lastPage();
});

$app->run();
