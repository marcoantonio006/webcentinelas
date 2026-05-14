<?php
session_start();
$auth = $_SESSION['login'] ?? false;
if (!$auth) { header('Location: /centinela/index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /centinela/admin/eventos/index.php'); exit;
}

require_once __DIR__ . '/../../src/Evento.php';
require_once __DIR__ . '/../../src/CSRF.php';

if (!CSRF::verificar()) die('Petición no válida');

$id = $_POST['id'] ?? null;
if (!$id) { header('Location: /centinela/admin/eventos/index.php'); exit; }

$datos = Evento::findById($id);

if ($datos) {
    // Borrar la imagen del servidor antes de eliminar el registro
    if (!empty($datos['imagen'])) {
        $ruta = __DIR__ . '/../../assets/img/eventos/' . $datos['imagen'];
        if (file_exists($ruta)) unlink($ruta);
    }

    Evento::eliminar($id);
}

header('Location: /centinela/admin/eventos/index.php');
exit;