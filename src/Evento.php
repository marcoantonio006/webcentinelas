<?php

require_once __DIR__ . '/DB.php';

class Evento {

    private $id;
    private $nombre;
    private $fecha;
    private $hora;
    private $lugar;
    private $equipo_local;
    private $equipo_visitante;
    private $imagen;

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function setLugar($lugar) {
        $this->lugar = $lugar;
    }

    public function setEquipoLocal($equipo_local) {
        $this->equipo_local = $equipo_local;
    }

    public function setEquipoVisit($equipo_visitante) {
        $this->equipo_visitante = $equipo_visitante;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getLugar() {
        return $this->lugar;
    }

    public function getEquipoLocal() {
        return $this->equipo_local;
    }

    public function getEquipoVisit() {
        return $this->equipo_visitante;
    }

    public function getImagen() {
        return $this->imagen;
    }
    
    public function guardar() {
        $conn = DB::conectar();

        $sql = 'INSERT INTO eventos(nombre, fecha, hora, lugar, equipo_local, equipo_visit, imagen) VALUES(?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);

        $datos = [
            $this->nombre,
            $this->fecha,
            $this->hora,
            $this->lugar,
            $this->equipo_local,
            $this->equipo_visitante,
            $this->imagen
        ];

        $stmt->bind_param( 'sssssss', ...$datos );

        $stmt->execute();
        $this->id = $conn->insert_id;
    }

    public function editar() {
        $conn = DB::conectar();
        $sql  = 'UPDATE eventos
                 SET nombre=?, fecha=?, hora=?, lugar=?,
                     equipo_local=?, equipo_visit=?, imagen=?
                 WHERE id=?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssi',
            $this->nombre,
            $this->fecha,
            $this->hora,
            $this->lugar,
            $this->equipo_local,
            $this->equipo_visit,
            $this->imagen,
            $this->id
        );
        $stmt->execute();
    }

    
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