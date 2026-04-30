<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}
require_once __DIR__ . '/../../src/CSRF.php';
require_once __DIR__ . '/../../src/Estudiante.php';

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

Estudiante::eliminar($id);

header('Location: /centinela/admin/estudiantes/index.php');
exit;