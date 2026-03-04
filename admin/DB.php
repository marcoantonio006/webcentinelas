<?php

class DB{
    private static $conn = null;

    public static function conectar(){
        if(self::$conn == null){
            self::$conn = new mysqli('localhost','root','','centinela');
        }

        return self::$conn;
    }
}

?>