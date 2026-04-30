<?php

require_once __DIR__ . '/DB.php';

class Estudiante {
    private $id;
    private $nombre;
    private $apellido;
    private $cedula;
    private $fecha_nacimiento;
    private $lugar_nacimiento;
    private $nombre_representante;
    private $apellido_representante;
    private $cedula_representante;
    private $profesion;
    private $domicilio;
    private $categoria_id;

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setCedula($cedula) {
        $this->cedula = $cedula;
    }

    public function setFechaNacimiento($fecha_nacimiento) {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function setLugarNacimiento($lugar_nacimiento) {
        $this->lugar_nacimiento = $lugar_nacimiento;
    }

    public function setNombreRepresentante($nombre_representante) {
        $this->nombre_representante = $nombre_representante;
    }

    public function setApellidoRepresentante($apellido_representante) {
        $this->apellido_representante = $apellido_representante;
    }

    public function setCedulaRepresentante($cedula_representante) {
        $this->cedula_representante = $cedula_representante;
    }

    public function setProfesion($profesion) {
        $this->profesion = $profesion;
    }

    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;
    }

    public function setCategoriaId($categoria_id) {
        $this->categoria_id = $categoria_id;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getCedula() {
        return $this->cedula;
    }

    public function getFechaNacimiento() {
        return $this->fecha_nacimiento;
    }

    public function getLugarNacimiento() {
        return $this->lugar_nacimiento;
    }

    public function getNombreRepresentante() {
        return $this->nombre_representante;
    }

    public function getApellidoRepresentante() {
        return $this->apellido_representante;
    }

    public function getCedulaRepresentante() {
        return $this->cedula_representante;
    }

    public function getProfesion() {
        return $this->profesion;
    }

    public function getDomicilio() {
        return $this->domicilio;
    }

    public function getCategoriaId() {
        return $this->categoria_id;
    }

    public function guardar() {
        $conn = DB::conectar();

        $sql = 'INSERT INTO estudiantes(nombre, apellido, cedula, fecha_nacimiento,lugar_nacimiento, nombre_representante, apellido_representante, cedula_representante, profesion, domicilio, categoria_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);

        $datos = [
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento,
            $this->nombre_representante,
            $this->apellido_representante,
            $this->cedula_representante,
            $this->profesion,
            $this->domicilio,
            $this->categoria_id
        ];

        $stmt->bind_param('ssssssssssi', ...$datos);

        $stmt->execute();

        
        if ($conn->errno === 1062) {
            return false;
        }

        return true;
    }

    public function editar() {
        $conn = DB::conectar();

        $sql = 'UPDATE estudiantes SET nombre = ?, apellido = ?, cedula = ?, fecha_nacimiento = ?, lugar_nacimiento = ?, nombre_representante = ?, apellido_representante = ?, cedula_representante = ?, profesion = ?, domicilio = ?, categoria_id = ? WHERE id = ?';

        $stmt = $conn->prepare($sql);

        $datos = [
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento,
            $this->nombre_representante,
            $this->apellido_representante,
            $this->cedula_representante,
            $this->profesion,
            $this->domicilio,
            $this->categoria_id,
            $this->id
        ];

        $stmt->bind_param('ssssssssssii', ...$datos);

        $stmt->execute();

        
    }

    public static function listar() {
        $conn = DB::conectar();

        $sql = 'SELECT estudiantes.*, categorias.nombre AS categoria_nombre FROM estudiantes LEFT JOIN categorias ON estudiantes.categoria_id = categorias.id ORDER BY estudiantes.apellido ASC';

        return $conn->query($sql);
    }

    public static function findById($id) {
        $conn = DB::conectar();

        $sql = 'SELECT estudiantes.*, categorias.nombre AS categoria_nombre FROM estudiantes LEFT JOIN categorias ON estudiantes.categoria_id = categorias.id WHERE estudiantes.id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id) {
        $conn = DB::conectar();

        $sql = 'DELETE FROM estudiantes WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }


}
