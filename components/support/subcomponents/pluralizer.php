<?php

use Illuminate\Support\Pluralizer;

// Pluralizer
echo '<h2>Pluralizer</h2>';
echo '<pre>';

$item = 'goose';
echo "One $item, two " . Pluralizer::plural($item) . "\n";
$item = 'moose';
echo "One $item, two " . Pluralizer::plural($item) . "\n";

echo '</pre><hr>';
