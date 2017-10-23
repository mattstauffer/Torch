<?php

use Illuminate\Support\Str;

// Str
echo '<h2>Str</h2>';
echo '<pre>';

if (Str::contains('This is my fourteenth visit', 'first')) {
    echo 'Howdy!';
} else {
    echo 'Nice to see you again.';
}

echo '</pre><hr>';
