<?php

    use DI\ContainerBuilder;
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $containerBuilder = new ContainerBuilder();

    $definitions = [];
    foreach (glob(__DIR__ . '/../Dependencies/*Dependencies.php') as $file) {
        $definitions = array_merge($definitions, require $file);
    }

    $definitions[PDO::class] = function () {
        $db = [
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASS'],
            'port' => $_ENV['DB_PORT'],
        ];
        $dsn = "mysql:host={$db['host']};dbname={$db['name']};port={$db['port']};charset=utf8mb4";
        return new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    };

    $containerBuilder->addDefinitions($definitions);

    return $containerBuilder->build();

?>