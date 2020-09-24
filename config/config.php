<?php

return [

    'settings' => [
        'displayErrorDetails' => (bool)getenv('DISPLAY_ERRORS'),
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => (string) getenv('DATABASE_NAME'),
            'username' => (string) getenv('DATABASE_USER'),
            'password' => (string) getenv('DATABASE_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],

];