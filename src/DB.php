<?php

class DB
{
    private static ?mysqli $conn = null;

    private function __construct() {}

    private function __clone() {}

    public static function conectar(): mysqli
    {
        if (self::$conn === null) {
            
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $config = require __DIR__ . '/../config.php';

            try {
                self::$conn = new mysqli(
                    $config['db_host'],
                    $config['db_user'],
                    $config['db_pass'],
                    $config['db_name']
                );
                
                self::$conn->set_charset('utf8mb4');

            } catch (mysqli_sql_exception $e) {
                error_log('Error de conexión: ' . $e->getMessage());
                
                throw new RuntimeException('No se pudo conectar a la base de datos.');
            }
        }

        return self::$conn;
    }
}

?>