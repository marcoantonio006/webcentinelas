<?php

class DB{
    private static $conn = null;

    public static function conectar(){
        if(self::$conn == null){
            $config = require __DIR__ . '/../config.php';
            self::$conn = new mysqli(
                $config['db_host'],
                $config['db_user'],
                $config['db_pass'],
                $config['db_name']
            );

            if (self::$conn->connect_error) {
                error_log('Error de conexión: ' . self::$conn->connect_error);
                die('No se pudo conectar a la base de datos.');
            }
        }

        return self::$conn;
    }
}

?>