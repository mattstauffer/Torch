<?php

namespace App\Eloquent;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Class Encapsulator.
 *
 * @author Original encapsulation pattern contributed by Kayla Daniels
 */
class Encapsulator
{
    private static $conn;

    private function __construct()
    {
    }

    /**
     * Initialize capsule and store reference to connection.
     */
    public static function init()
    {
        if (is_null(self::$conn)) {
            $capsule = new Capsule();

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

            $capsule->setEventDispatcher(new Dispatcher(new Container()));

            // Set the cache manager instance used by connections... (optional)
            // $capsule->setCacheManager(...);

            // Make this Capsule instance available globally via static methods... (optional)
            $capsule->setAsGlobal();

            // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
            $capsule->bootEloquent();
        }
    }
}
