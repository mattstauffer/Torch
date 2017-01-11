<?php

return [
    'queue' => [
        'default' => 'sync',
        'connections' => [
            'sync' => [
                'driver' => 'sync',
            ],
            'beanstalkd' => [
                'driver' => 'beanstalkd',
                'host' => 'localhost',
                'queue' => 'default',
                'retry_after' => 90,
            ],
        ]
    ]
];
