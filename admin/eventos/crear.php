<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Evento.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $fecha  = trim($_POST['fecha']  ?? '');
    $lugar  = trim($_POST['lugar']  ?? '');

    if ($nombre === '') $errores[] = 'El nombre es obligatorio';
    if ($fecha === '')  $errores[] = 'La fecha es obligatoria';
    if ($lugar === '')  $errores[] = 'El lugar es obligatorio';

    if (empty($errores)) {
        $evento = new Evento();

        $evento->setNombre($nombre);
        $evento->setFecha($fecha);
        $evento->setLugar($lugar);

        $evento->guardar();

        header('Location: /centinela/admin/eventos/index.php');
        exit;
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Nuevo Evento</h1>

    <a class="boton" href="/centinela/admin/eventos/index.php">← Volver</a>

    <?php if (!empty($errores)) : ?>
        <?php foreach ($errores as $error) : ?>
            <p class="errores"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <div id="errores-evento"></div>

    <form class="formulario" method="POST" onsubmit="return validarEvento()">
        <fieldset>
            <legend>Datos del evento</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha"
                value="<?php echo htmlspecialchars($_POST['fecha'] ?? ''); ?>">

            <label for="lugar">Lugar:</label>
            <input type="text" id="lugar" name="lugar"
                value="<?php echo htmlspecialchars($_POST['lugar'] ?? ''); ?>">
        </fieldset>

        <button class="boton" type="submit">Guardar evento</button>
    </form>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>