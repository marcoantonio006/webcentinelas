<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/CSRF.php';
require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Representante.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

if (!CSRF::verificar()) {
    die('Petición no válida');
}

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

// Guardar el representante_id ANTES de borrar al atleta,
// porque después ya no podremos consultarlo
$representante_id = $datos['representante_id'] ?? null;

// 1. Borrar el atleta primero (la FK va de estudiantes → representantes,
//    así que hay que eliminar el hijo antes que el padre)
Estudiante::eliminar($id);

// 2. Si el atleta tenía representante, verificar si quedó sin atletas
//    y en ese caso eliminarlo también
if ($representante_id && Representante::estaHuerfano($representante_id)) {
    Representante::eliminar($representante_id);
}

header('Location: /centinela/admin/estudiantes/index.php');
exit;