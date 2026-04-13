<?php

require_once __DIR__ . '/../src/DB.php';

$conn = DB::conectar();

$correo = "centinelas@gmail.com";
$password = "root";

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$query = 'INSERT INTO usuarios(correo, password) VALUES(?,?)';
$stmt = $conn->prepare($query);

$datos = [
    $correo,
    $passwordHash
];

$tiposDatos = 'ss';

$stmt->bind_param($tiposDatos, ...$datos);
$stmt->execute();

echo 'Usuario creado correctamente';