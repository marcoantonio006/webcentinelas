<?php

require_once __DIR__ . '/DB.php';

class Persona {

    private $id;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;

    // ── Setters ──────────────────────────────────
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setCedula($cedula) { $this->cedula = $cedula; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setCorreo($correo) { $this->correo = $correo; }

    // ── Getters ──────────────────────────────────
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getCedula() { return $this->cedula; }
    public function getTelefono() { return $this->telefono; }
    public function getCorreo() { return $this->correo; }

    // ── Métodos de instancia ──────────────────────
    public function guardar(): bool {
        $conn = DB::conectar();

        $sql = 'INSERT INTO personas(nombre, apellido, cedula, telefono, correo)
                VALUES(?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss',
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->telefono,
            $this->correo
        );

        try {
            $stmt->execute();
            $this->id = $conn->insert_id;
            return true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) return false;
            throw $e;
        }
    }

    public function editar(): void {
        $conn = DB::conectar();

        $sql = 'UPDATE personas SET
                    nombre = ?, apellido = ?, cedula = ?,
                    telefono = ?, correo = ?
                WHERE id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi',
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->telefono,
            $this->correo,
            $this->id
        );

        $stmt->execute();
    }

    // ── Métodos estáticos ─────────────────────────
    public static function findById($id) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM personas WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function findByCedula($cedula) {
        $conn = DB::conectar();
        $stmt = $conn->prepare('SELECT * FROM personas WHERE cedula = ?');
        $stmt->bind_param('s', $cedula);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id): void {
        $conn = DB::conectar();
        $stmt = $conn->prepare('DELETE FROM personas WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public static function estaHuerfana($id): bool {
        $conn = DB::conectar();

        $stmt = $conn->prepare('
            SELECT COUNT(*) FROM estudiantes WHERE persona_id = ?
            UNION ALL
            SELECT COUNT(*) FROM representantes WHERE persona_id = ?
        ');
        $stmt->bind_param('ii', $id, $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $total = 0;
        while ($fila = $resultado->fetch_row()) {
            $total += $fila[0];
        }

        return $total === 0;
    }
}