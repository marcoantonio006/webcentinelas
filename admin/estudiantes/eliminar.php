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
 
$persona_id       = $datos['persona_id'];
$representante_id = $datos['representante_id'];

Estudiante::eliminar($id);

if ($representante_id && Representante::estaHuerfano($representante_id)) {
    $repDatos       = Representante::findById($representante_id);
    $rep_persona_id = $repDatos['persona_id'] ?? null;

    Representante::eliminar($representante_id);

    if ($rep_persona_id && Persona::estaHuerfana($rep_persona_id)) {
        Persona::eliminar($rep_persona_id);
    }
}

if ($persona_id && Persona::estaHuerfana($persona_id)) {
    Persona::eliminar($persona_id);
}

header('Location: /centinela/admin/estudiantes/index.php');
exit;