<?php

return [
    
    'default' => env('DB_CONNECTION', 'dlab'),

    'connections' => [

        'dlab' => [
            'driver'    => env('DB_DRIVER', 'mysql'),
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'dlab'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
        ],

    ],

];