<?php

use Illuminate\Support\Str;

return [

    'default' => 'muonline',

    'connections' => [

        'memuonline' => [
            'driver'   => env('DB_DRIVER', 'sqlsrv'),
            'host'     => env('DB_ACCOUNT_HOST', '127.0.0.1'),
            'port'     => env('DB_ACCOUNT_PORT', '1433'),
            'database' => env('DB_ACCOUNT_NAME', 'Me_MuOnline'),
            'username' => env('DB_ACCOUNT_USER', 'sa'),
            'password' => env('DB_ACCOUNT_PASS', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'prefix_indexes' => true,
        ],

        'muonline' => [
            'driver'   => env('DB_DRIVER', 'sqlsrv'),
            'host'     => env('DB_GAME_HOST', '127.0.0.1'),
            'port'     => env('DB_GAME_PORT', '1433'),
            'database' => env('DB_GAME_NAME', 'MuOnline'),
            'username' => env('DB_GAME_USER', 'sa'),
            'password' => env('DB_GAME_PASS', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'prefix_indexes' => true,
        ],

    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster'    => env('REDIS_CLUSTER', 'redis'),
            'prefix'     => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-'),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url'      => env('REDIS_URL'),
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port'     => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
