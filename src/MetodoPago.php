<?php

require_once __DIR__ . '/DB.php';

class MetodoPago
{

    private $id;
    private $nombre;
    private $descripcion;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function guardar(): bool
    {
        $conn = DB::conectar();

        $sql = 'INSERT INTO metodos_pago(nombre, descripcion) VALUES(?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $this->nombre, $this->descripcion);

        try {
            $stmt->execute();
            $this->id = $conn->insert_id;
            return true;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function editar(): void
    {
        $conn = DB::conectar();

        $sql = 'UPDATE metodos_pago SET nombre = ?, descripcion = ? WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $this->nombre, $this->descripcion, $this->id);
        $stmt->execute();
    }

    public static function listar()
    {
        $conn = DB::conectar();
        return $conn->query('SELECT * FROM metodos_pago');
    }

    public static function findById($id)
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM metodos_pago WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id): void
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM metodos_pago WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
