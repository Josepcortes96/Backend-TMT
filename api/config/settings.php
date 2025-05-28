<?php

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv -> load();

    return [
            'db' => [
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASS']
            ]
        ];
?>