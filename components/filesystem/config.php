<?php
return [
    'filesystems' => [
        'default' => 'local',

        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => __DIR__.'/storage',
            ],
        ]
    ]
];
