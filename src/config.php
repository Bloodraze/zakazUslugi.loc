<?php

$config = [
    'db' => [
        'hostname' => 'localhost',
        'database' => 'clinic_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],

    'app' => [
        'name' => 'My Application',
        'admin_email' => 'admin@example.com',
        'base_url' => 'http://zakazuslugi.loc',
    ],

    'paths' => [
        'src' => __DIR__,
        'root' => dirname(__DIR__),
    ]
];

$dbOptions = $config['db'];

return $config;