<?php

return [

    'server_name'    => env('SERVER_NAME', 'My MuOnline Server'),
    'server_season'  => env('SERVER_SEASON', 6),
    'server_files'   => env('SERVER_FILES', 'igcn'), // igcn, xteam, custom
    'admin_usernames' => array_filter(explode(',', env('ADMIN_USERNAMES', 'admin'))),

    'features' => [
        'registration'       => true,
        'email_verification' => false,
        'player_profiles'    => true,
        'guild_profiles'     => true,
        'castle_siege'       => true,
        'donations'          => true,
        'votes'              => true,
        'rankings'           => true,
        'downloads'          => true,
    ],

    'rankings' => [
        'limit'            => 100,
        'excluded_chars'   => [],
        'excluded_guilds'  => [],
    ],

    'session' => [
        'timeout_minutes' => 60,
    ],

    'brute_force' => [
        'max_attempts'    => 5,
        'lockout_minutes' => 15,
    ],

    // Secret token for the HTTP cron trigger endpoint (/api/cron?token=xxx).
    // Set CRON_TOKEN in .env. If left null, the endpoint runs without auth (not recommended).
    'cron_token' => env('CRON_TOKEN'),

    'paypal' => [
        'email'           => env('PAYPAL_EMAIL', ''),
        'sandbox'         => env('PAYPAL_SANDBOX', false),
        'currency'        => env('PAYPAL_CURRENCY', 'USD'),
        'conversion_rate' => env('PAYPAL_CONVERSION_RATE', 1),  // credits per $ (floor applied)
        'credit_config_id'=> env('PAYPAL_CREDIT_CONFIG_ID', 1),
        'return_url'      => env('PAYPAL_RETURN_URL', ''),
        'item_name'       => env('PAYPAL_ITEM_NAME', 'Donation'),
    ],

];
