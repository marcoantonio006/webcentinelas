<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Representante.php';
require_once __DIR__ . '/../../src/Persona.php';
require_once __DIR__ . '/../../src/CSRF.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

if (!CSRF::verificar()) die('Petición no válida');

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

$datos = Estudiante::findById($id);

if (!$datos) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

// Guardar ids antes de eliminar
$persona_id       = $datos['persona_id'];
$representante_id = $datos['representante_id'];

// 1. Eliminar el estudiante
Estudiante::eliminar($id);

// 2. Si el representante quedó sin atletas, eliminarlo
if ($representante_id) {
    // Verificar si el representante tiene otros atletas
    $conn = DB::conectar();
    $stmt = $conn->prepare('SELECT COUNT(*) FROM estudiantes WHERE representante_id = ?');
    $stmt->bind_param('i', $representante_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count === 0) {
        // Obtener persona_id del representante antes de eliminarlo
        $repDatos = Representante::findById($representante_id);
        $rep_persona_id = $repDatos['persona_id'] ?? null;

        Representante::eliminar($representante_id);

        // 3. Si esa persona no tiene otros roles, eliminarla también
        if ($rep_persona_id && Persona::estaHuerfana($rep_persona_id)) {
            Persona::eliminar($rep_persona_id);
        }
    }
}

// 4. Si la persona del atleta no tiene otros roles, eliminarla
if ($persona_id && Persona::estaHuerfana($persona_id)) {
    Persona::eliminar($persona_id);
}

header('Location: /centinela/admin/estudiantes/index.php');
exit;