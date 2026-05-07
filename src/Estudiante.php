<?php

require_once __DIR__ . '/DB.php';

class Estudiante {

    private $id;
    private $nombre;
    private $apellido;
    private $cedula;
    private $fecha_nacimiento;
    private $lugar_nacimiento;
    private $categoria_id;
    private $representante_id;

    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setCedula($cedula) { $this->cedula = $cedula; }
    public function setFechaNacimiento($fecha_nacimiento) { $this->fecha_nacimiento = $fecha_nacimiento; }
    public function setLugarNacimiento($lugar_nacimiento) { $this->lugar_nacimiento = $lugar_nacimiento; }
    public function setCategoriaId($categoria_id) { $this->categoria_id = $categoria_id; }
    public function setRepresentanteId($representante_id) { $this->representante_id = $representante_id; }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getCedula() { return $this->cedula; }
    public function getFechaNacimiento() { return $this->fecha_nacimiento; }
    public function getLugarNacimiento() { return $this->lugar_nacimiento; }
    public function getCategoriaId() { return $this->categoria_id; }
    public function getRepresentanteId() { return $this->representante_id; }

    public function guardar(): bool {
        $conn = DB::conectar();

        $sql = 'INSERT INTO estudiantes(nombre, apellido, cedula, fecha_nacimiento, lugar_nacimiento, categoria_id, representante_id) VALUES(?, ?, ?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);

        $datos = [
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento,
            $this->categoria_id,
            $this->representante_id
        ];

        $stmt->bind_param('sssssii', ...$datos);

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

    public function editar(): bool {
        
        $conn = DB::conectar();

        $sql = 'UPDATE estudiantes SET nombre = ?, apellido = ?, cedula = ?, fecha_nacimiento = ?, lugar_nacimiento = ?, categoria_id = ?, representante_id = ? WHERE id = ?';

        $stmt = $conn->prepare($sql);

        $datos = [
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento,
            $this->categoria_id,
            $this->representante_id,
            $this->id
        ];

        $stmt->bind_param('sssssiii', ...$datos);

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

        $sql = 'SELECT estudiantes.*, 
                    categorias.nombre   AS categoria_nombre,
                    representantes.nombre   AS rep_nombre,
                    representantes.apellido AS rep_apellido,
                    representantes.cedula   AS rep_cedula,
                    representantes.profesion AS rep_profesion,
                    representantes.domicilio AS rep_domicilio
                FROM estudiantes
                LEFT JOIN categorias    ON estudiantes.categoria_id    = categorias.id
                LEFT JOIN representantes ON estudiantes.representante_id = representantes.id
                ORDER BY estudiantes.apellido ASC';

        return $conn->query($sql);
    }

    public static function findById($id) {
        $conn = DB::conectar();

        $sql = 'SELECT estudiantes.*,
                    categorias.nombre    AS categoria_nombre,
                    representantes.nombre    AS rep_nombre,
                    representantes.apellido  AS rep_apellido,
                    representantes.cedula    AS rep_cedula,
                    representantes.profesion AS rep_profesion,
                    representantes.domicilio AS rep_domicilio
                FROM estudiantes
                LEFT JOIN categorias     ON estudiantes.categoria_id    = categorias.id
                LEFT JOIN representantes ON estudiantes.representante_id = representantes.id
                WHERE estudiantes.id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id) {
        $conn = DB::conectar();

        $stmt = $conn->prepare('DELETE FROM estudiantes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}