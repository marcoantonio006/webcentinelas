<?php

session_start();

$auth = $_SESSION['login'];

if(!$auth){
    header('Location: /');
}

include '../../templates/header.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha = $_POST['fecha'];
    $representante = $_POST['representante'];

    // Aquí puedes agregar la lógica para guardar los datos en una base de datos o generar la constancia
    // Por ejemplo, podrías usar una función para crear un PDF con los datos ingresados

    echo "<p>Constancia creada para $nombre $apellido el $fecha por $representante.</p>";
}

?>

<main class="contenedor seccion">

    <a class="boton" href="../index.php">&larr;  Volver</a>

    <form class="formulario" action="create.php" method="POST">
        <fieldset>
            <legend>Datos del estudiante</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name ="apellido">

        </fieldset>

        <fieldset>
            <legend>Datos de la constancia</legend>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha">

            <label for="representante">Representante de la academia:</label>
            <input type="text" id="representante" name ="representante">

        </fieldset>
        <button type="submit" class="boton">Crear constancia</button>
    </form>
</main>

<?php

include '../../templates/footer.php';

?>