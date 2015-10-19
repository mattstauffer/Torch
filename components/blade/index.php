<?php

use Philo\Blade\Blade;

require_once 'vendor/autoload.php';

$views = 'template';
$cache = 'cache';

$tpl = new Blade($views, $cache);

$view_data['value'] = 'bar';
$view_data['value2'] = 'foo';

$view_filename = 'hello';

try {
    // Load the view within the current scope
    echo $tpl->view()
         ->make($view_filename)
         ->with($view_data)
         ->render();
    }
catch (Exception $e) {
    // Re-throw the exception
     throw $e;
}