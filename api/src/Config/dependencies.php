<?php
    use DI\Container;
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable( __DIR__ . '/../../');
    $dotenv->load();

    $settings = require __DIR__ . '/settings.php';
    $container = new Container();

    $container ->set(\PDO::class, function () use ($settings){
        $db = $settings['db'];
        $dsn = "mysql:host={$db['host']};dbname={$db['name']};port={$db['port']};charset=utf8mb4";
        
        return new \PDO($dsn, $db['user'], $db['pass'], [
            \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    });

    return $container;
?>

