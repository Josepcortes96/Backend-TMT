<?php
namespace App\Utils;

use PDO;
use Exception;

class ConnectionTest
{
    public static function test(PDO $pdo): void
    {
        try {
            $stmt = $pdo->query("SELECT NOW()");
            $result = $stmt->fetchColumn();
            echo "Conexión exitosa.";
        } catch (Exception $e) {
            echo "❌ Error de conexión: " . $e->getMessage() . "\n";
        }
    }
}
?>