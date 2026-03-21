<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'accounts',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'accounts',
        ],
    ],

    'providers' => [
        'accounts' => [
            'driver' => 'muonline',
        ],
    ],

    'passwords' => [
        'accounts' => [
            'provider' => 'accounts',
            'table'    => 'WEBENGINE_PASSCHANGE_REQUEST',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
