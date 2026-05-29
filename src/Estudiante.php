<?php

require_once __DIR__ . '/DB.php';



class Estudiante
{

    private $id;
    private $persona_id;
    private $categoria_id;
    private $representante_id;
    private $fecha_nacimiento;
    private $lugar_nacimiento;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setPersonaId($persona_id)
    {
        $this->persona_id = $persona_id;
    }
    public function setCategoriaId($categoria_id)
    {
        $this->categoria_id = $categoria_id;
    }
    public function setRepresentanteId($representante_id)
    {
        $this->representante_id = $representante_id;
    }
    public function setFechaNacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }
    public function setLugarNacimiento($lugar_nacimiento)
    {
        $this->lugar_nacimiento = $lugar_nacimiento;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getPersonaId()
    {
        return $this->persona_id;
    }
    public function getCategoriaId()
    {
        return $this->categoria_id;
    }
    public function getRepresentanteId()
    {
        return $this->representante_id;
    }
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }
    public function getLugarNacimiento()
    {
        return $this->lugar_nacimiento;
    }

    public function guardar()
    {
        $conn = DB::conectar();

        $sql = 'INSERT INTO estudiantes(
                    persona_id, categoria_id, representante_id,
                    fecha_nacimiento, lugar_nacimiento
                ) VALUES(?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'iiiss',
            $this->persona_id,
            $this->categoria_id,
            $this->representante_id,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento
        );

        $stmt->execute();
        return true;
    }

    public function editar()
    {
        $conn = DB::conectar();

        $sql = 'UPDATE estudiantes SET
                    persona_id = ?, categoria_id = ?, representante_id = ?,
                    fecha_nacimiento = ?, lugar_nacimiento = ?
                WHERE id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'iiissi',
            $this->persona_id,
            $this->categoria_id,
            $this->representante_id,
            $this->fecha_nacimiento,
            $this->lugar_nacimiento,
            $this->id
        );

        try {
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public static function listar()
    {
        $conn = DB::conectar();

        $sql = 'SELECT
                    e.id,
                    e.persona_id,
                    e.categoria_id,
                    e.representante_id,
                    e.fecha_nacimiento,
                    e.lugar_nacimiento,
                    p.nombre           AS nombre,
                    p.apellido         AS apellido,
                    p.cedula           AS cedula,
                    p.telefono         AS telefono,
                    p.correo           AS correo,
                    c.nombre           AS categoria_nombre,
                    rp.nombre          AS rep_nombre,
                    rp.apellido        AS rep_apellido,
                    rp.cedula          AS rep_cedula,
                    rp.telefono        AS rep_telefono,
                    rp.correo          AS rep_correo,
                    r.profesion        AS rep_profesion,
                    r.domicilio        AS rep_domicilio
                FROM estudiantes e
                LEFT JOIN personas       p  ON e.persona_id       = p.id
                LEFT JOIN categorias     c  ON e.categoria_id     = c.id
                LEFT JOIN representantes r  ON e.representante_id = r.id
                LEFT JOIN personas       rp ON r.persona_id       = rp.id
                ORDER BY p.nombre ASC';

        return $conn->query($sql);
    }

    public static function findById($id)
    {
        $conn = DB::conectar();

        $sql = 'SELECT
                    e.id,
                    e.persona_id,
                    e.categoria_id,
                    e.representante_id,
                    e.fecha_nacimiento,
                    e.lugar_nacimiento,
                    p.nombre           AS nombre,
                    p.apellido         AS apellido,
                    p.cedula           AS cedula,
                    p.telefono         AS telefono,
                    p.correo           AS correo,
                    c.nombre           AS categoria_nombre,
                    rp.nombre          AS rep_nombre,
                    rp.apellido        AS rep_apellido,
                    rp.cedula          AS rep_cedula,
                    rp.telefono        AS rep_telefono,
                    rp.correo          AS rep_correo,
                    r.profesion        AS rep_profesion,
                    r.domicilio        AS rep_domicilio
                FROM estudiantes e
                LEFT JOIN personas       p  ON e.persona_id       = p.id
                LEFT JOIN categorias     c  ON e.categoria_id     = c.id
                LEFT JOIN representantes r  ON e.representante_id = r.id
                LEFT JOIN personas       rp ON r.persona_id       = rp.id
                WHERE e.id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findByPersonaId($persona_id)
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT id FROM estudiantes WHERE persona_id = ?');
        $stmt->bind_param('i', $persona_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id)
    {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM estudiantes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
