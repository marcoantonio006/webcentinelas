<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Evento.php';


$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/eventos/index.php');
    exit;
}

$datos = Evento::findById($id);

if (!$datos) {
    header('Location: /centinela/admin/eventos/index.php');
    exit;
}

Evento::eliminar($id);

header('Location: /centinela/admin/eventos/index.php');
exit;