<?php

require_once __DIR__ . '/DB.php';

class PlanPagos
{

    private $id;
    private $nombre;
    private $precio;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getPrecio()
    {
        return $this->precio;
    }

    public function guardar(): bool
    {
        $conn = DB::conectar();

        $sql = 'INSERT INTO planes(nombre, precio) VALUES(?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sd', $this->nombre, $this->precio);

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

        $sql = 'UPDATE planes SET nombre = ?, precio = ? WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdi', $this->nombre, $this->precio, $this->id);
        $stmt->execute();
    }

    public static function listar()
    {
        $conn = DB::conectar();
        return $conn->query('SELECT * FROM planes');
    }

    public static function findById($id)
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM planes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id): void
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM planes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
