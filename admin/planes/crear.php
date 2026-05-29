<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/PlanPagos.php';
require_once __DIR__ . '/../../src/CSRF.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre = trim($_POST['nombre'] ?? '');
    $precio = trim($_POST['precio'] ?? '');

    if ($nombre === '') $errores[] = 'El nombre es obligatorio';
    if ($precio === '') $errores[] = 'El precio es obligatorio';
    elseif (!is_numeric($precio) || $precio < 0)
                        $errores[] = 'El precio debe ser un número positivo';

    if (empty($errores)) {
        $plan = new PlanPagos();
        $plan->setNombre($nombre);
        $plan->setPrecio($precio);
        $plan->guardar();

        header('Location: /centinela/admin/planes/index.php');
        exit;
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Nuevo Plan</h1>

    <a class="boton" href="/centinela/admin/planes/index.php">← Volver</a>

    <div id="errores-plan"></div>

    <?php foreach ($errores as $error) : ?>
        <p class="errores"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form class="formulario" method="POST" onsubmit="return validarPlan()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Datos del plan</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="precio">Precio ($):</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0"
                value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>">
        </fieldset>

        <button class="boton" type="submit">Guardar plan</button>
    </form>
</main>
