<?php

require_once __DIR__ . '/DB.php';

class Representante {

    private $id;
    private $persona_id;
    private $profesion;
    private $domicilio;

    public function setId($id) { $this->id = $id; }
    public function setPersonaId($persona_id) { $this->persona_id = $persona_id; }
    public function setProfesion($profesion) { $this->profesion = $profesion; }
    public function setDomicilio($domicilio) { $this->domicilio = $domicilio; }

    public function getId() { return $this->id; }
    public function getPersonaId() { return $this->persona_id; }
    public function getProfesion() { return $this->profesion; }
    public function getDomicilio() { return $this->domicilio; }

    public function guardar() {
        $conn = DB::conectar();

        $sql = 'INSERT INTO representantes(persona_id, profesion, domicilio)
                VALUES(?, ?, ?)';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss',
            $this->persona_id,
            $this->profesion,
            $this->domicilio
        );

        try {
            $stmt->execute();
            $this->id = $conn->insert_id;
            return true;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function editar() {
        $conn = DB::conectar();

        $sql = 'UPDATE representantes SET
                    profesion = ?, domicilio = ?
                WHERE id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi',
            $this->profesion,
            $this->domicilio,
            $this->id
        );

        $stmt->execute();
    }

    public static function findById($id) {
        $conn = DB::conectar();

        $sql = 'SELECT r.*, p.nombre, p.apellido, p.cedula,
                       p.telefono, p.correo
                FROM representantes r
                JOIN personas p ON r.persona_id = p.id
                WHERE r.id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findByPersonaCedula($cedula) {
        $conn = DB::conectar();

        $sql = 'SELECT r.*
                FROM representantes r
                JOIN personas p ON r.persona_id = p.id
                WHERE p.cedula = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $cedula);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id): void {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM representantes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}