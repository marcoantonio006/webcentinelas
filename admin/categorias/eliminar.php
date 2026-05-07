<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /centinela/admin/categorias/index.php');
    exit;
}

if (!CSRF::verificar()) {
    die('Petición no válida');
}

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/categorias/index.php');
    exit;
}

$datos = Categoria::findById($id);

if (!$datos) {
    header('Location: /centinela/admin/categorias/index.php');
    exit;
}

try {
    Categoria::eliminar($id);
} catch (mysqli_sql_exception $e) {
    header('Location: /centinela/admin/categorias/index.php?error=tiene_atletas');
    exit;
}

header('Location: /centinela/admin/categorias/index.php');
exit;