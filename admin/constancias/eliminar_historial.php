<?php
session_start();
$auth = $_SESSION['login'] ?? false;
if (!$auth) { header('Location: /centinela/index.php'); exit; }

require_once __DIR__ . '/../../src/HistorialConstancia.php';
require_once __DIR__ . '/../../src/CSRF.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /centinela/admin/constancias/historial.php'); 
    exit;
}

if (!CSRF::verificar()) die('Petición no válida');

$id = (int)($_POST['id'] ?? 0);
if ($id) HistorialConstancia::eliminar($id);

header('Location: /centinela/admin/constancias/historial.php');
exit;