<?php

require_once __DIR__ . '/DB.php';

class Representante {

    private $id;
    private $nombre;
    private $apellido;
    private $cedula;
    private $profesion;
    private $domicilio;


    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setCedula($cedula) { $this->cedula = $cedula; }
    public function setProfesion($profesion) { $this->profesion = $profesion; }
    public function setDomicilio($domicilio) { $this->domicilio = $domicilio; }


    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getCedula() { return $this->cedula; }
    public function getProfesion() { return $this->profesion; }
    public function getDomicilio() { return $this->domicilio; }

    public function guardar() {
        $conn = DB::conectar();

        $sql = 'INSERT INTO representantes(nombre, apellido, cedula, profesion, domicilio) VALUES(?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss',
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->profesion,
            $this->domicilio
        );

        try {
            $stmt->execute();
            $this->id = $conn->insert_id; 
            return true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                return false; 
            }
            throw $e;
        }
    }

    public function editar() {
        $conn = DB::conectar();

        $sql = 'UPDATE representantes SET nombre = ?, apellido = ?, cedula = ?, profesion = ?, domicilio = ? WHERE id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi',
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->profesion,
            $this->domicilio,
            $this->id
        );

        try {
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                return false; 
            }
            throw $e;
        }
    }

    public static function listar() {
        $conn = DB::conectar();
        return $conn->query('SELECT * FROM representantes ORDER BY apellido ASC');
    }

    public static function findById($id) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM representantes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findByCedula($cedula) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM representantes WHERE cedula = ?');
        $stmt->bind_param('s', $cedula);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM representantes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    
    public static function estaHuerfano($id) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM estudiantes WHERE representante_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count === 0;
    }
}