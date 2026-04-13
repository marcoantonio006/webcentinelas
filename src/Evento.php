<?php

require_once __DIR__ . '/DB.php';

class Evento {

    private $id;
    private $nombre;
    private $fecha;
    private $lugar;

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setLugar($lugar) {
        $this->lugar = $lugar;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getLugar() {
        return $this->lugar;
    }

    // Métodos de instancia
    public function guardar() {
        $conn = DB::conectar();

        $sql = 'INSERT INTO eventos(nombre, fecha, lugar) VALUES(?, ?, ?)';
        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            'sss',
            $this->nombre,
            $this->fecha,
            $this->lugar
        );

        $stmt->execute();
    }

    public function editar() {
        $conn = DB::conectar();

        $sql = 'UPDATE eventos SET nombre = ?, fecha = ?, lugar = ? WHERE id = ?';
        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            'sssi',
            $this->nombre,
            $this->fecha,
            $this->lugar,
            $this->id
        );

        $stmt->execute();
    }

    // Métodos estáticos
    public static function listar() {
        $conn = DB::conectar();

        $sql = 'SELECT * FROM eventos ORDER BY fecha ASC';

        return $conn->query($sql);
    }

    public static function findById($id) {
        $conn = DB::conectar();

        $sql = 'SELECT * FROM eventos WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id) {
        $conn = DB::conectar();

        $sql = 'DELETE FROM eventos WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}