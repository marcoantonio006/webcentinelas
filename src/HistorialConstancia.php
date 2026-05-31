<?php

require_once __DIR__ . '/DB.php';

class HistorialConstancia {

    public static function guardar(array $datos): void {
        
        $json = json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS);

        $nombre = trim(($datos['nombre_atleta'] ?? '') . ' ' . ($datos['apellido_atleta'] ?? ''));
        $torneo = $datos['nombre_torneo'] ?? '';

        $conn = DB::conectar();
        $stmt = $conn->prepare(
            'INSERT INTO historial_constancias
                (nombre_atleta, nombre_torneo, datos_json)
             VALUES (?, ?, ?)'
        );
        $stmt->bind_param('sss', $nombre, $torneo, $json);
        $stmt->execute();
    }

    public static function listar() {
        return DB::conectar()->query(
            'SELECT id, nombre_atleta, nombre_torneo, datos_json, generada_en
             FROM historial_constancias
             ORDER BY generada_en DESC'
        );
    }

    public static function eliminar(int $id): void {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM historial_constancias WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}