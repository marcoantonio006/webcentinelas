<?php

session_start();

$auth = $_SESSION['login'];

if(!$auth){
    header('Location: /');
}

include '../templates/header.php';

?>

<body>
    <main class="contenedor seccion">
        <h1>Panel de Administrador</h1>

        <!-- <a class="boton" href="../admin/estudiantes/create.php">Nuevo Estudiante</a> -->

        <a class="boton" href="../admin/constancias/create.php">Crear constancia</a>
        
    </main>

<?php

include '../templates/footer.php';

?>