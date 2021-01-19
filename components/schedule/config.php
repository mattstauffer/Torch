<?php

return [
    'cache' => [
        'default' => 'array',
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => __DIR__.'/cache',
            ],
        ]
    ]
];