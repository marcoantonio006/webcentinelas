<?php

require_once __DIR__ . '/DB.php';

class Categoria {

    public static function listar() {
        $conn = DB::conectar();
        return $conn->query('SELECT id, nombre FROM categorias ORDER BY nombre ASC');
    }
}