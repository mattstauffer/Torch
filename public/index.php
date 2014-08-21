<?php
$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) {
	    echo "Hello, $name";
});
$app->run();