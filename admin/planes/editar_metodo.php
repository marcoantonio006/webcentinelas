<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/MetodoPago.php';
require_once __DIR__ . '/../../src/CSRF.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/planes/index.php');
    exit;
}

$datos = MetodoPago::findById($id);

if (!$datos) {
    header('Location: /centinela/admin/planes/index.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre      = trim($_POST['nombre']      ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre === '')      $errores[] = 'El nombre es obligatorio';
    if ($descripcion === '') $errores[] = 'La descripción es obligatoria';

    if (empty($errores)) {
        $metodo = new MetodoPago();
        $metodo->setId($id);
        $metodo->setNombre($nombre);
        $metodo->setDescripcion($descripcion);
        $metodo->editar();

        header('Location: /centinela/admin/planes/index.php');
        exit;
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Editar Método de Pago</h1>

    <a class="boton" href="/centinela/admin/planes/index.php">← Volver</a>

    <div id="errores-metodo"></div>

    <?php foreach ($errores as $error) : ?>
        <p class="errores"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form class="formulario" method="POST" onsubmit="return validarMetodoPago()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Datos del método de pago</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? $datos['nombre']); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"
                rows="4"><?php echo htmlspecialchars($_POST['descripcion'] ?? $datos['descripcion']); ?></textarea>
        </fieldset>

        <button class="boton" type="submit">Guardar cambios</button>
    </form>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>