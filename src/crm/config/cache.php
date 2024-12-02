<?php

use App\Core\Cache\File;
use App\Core\Cache\Redis;

$defaultDriver = 'redis';

return [
    'driver' => [
        'redis' => [
            'options' => [
                'scheme' => 'tcp',
                'host' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'password' => getenv('REDIS_PASSWORD'),
            ],
            'default-db' => 1,
            'class' => Redis::class,
        ],
        'file' => [
            'options' => [
                'path' => APP_PATH . '/storage/cache',
            ],
            'default-db' => null,
            'class' => File::class,
        ],
    ],
    
    'default-time' => 20,
    'default-driver' => $defaultDriver,
    'enabled' => true,
];