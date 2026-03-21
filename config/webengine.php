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

];
