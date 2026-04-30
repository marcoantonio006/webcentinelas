<?php

session_start();

$auth = $_SESSION['login'];

if(!$auth){
    header('Location: /centinela/index.php');
    exit;
}

include '../templates/header.php';

?>

<body>
    <main class="contenedor seccion">
        <h1>Panel de Administrador</h1>

        <div class="centrado">
            <a class="boton" href="../admin/estudiantes/">Administrar Atletas</a>
            <a class="boton" href="../admin/eventos/">Administrar Eventos</a>

        </div>
        
    </main>

