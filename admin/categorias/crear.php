<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre      = trim($_POST['nombre']      ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre === '')      $errores[] = 'El nombre es obligatorio';
    if ($descripcion === '') $errores[] = 'La descripción es obligatoria';

    if (empty($errores)) {
        $categoria = new Categoria();
        $categoria->setNombre($nombre);
        $categoria->setDescripcion($descripcion);

        if (!$categoria->guardar()) {
            $errores[] = 'Ya existe una categoría con ese nombre';
        } else {
            header('Location: /centinela/admin/categorias/index.php');
            exit;
        }
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Nueva Categoría</h1>

    <a class="boton" href="/centinela/admin/categorias/index.php">← Volver</a>

    <div id="errores-categoria"></div>

    <?php foreach ($errores as $error) : ?>
        <p class="errores"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form class="formulario" method="POST" onsubmit="return validarCategoria()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Datos de la categoría</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"
                rows="3"><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
        </fieldset>

        <button class="boton" type="submit">Guardar categoría</button>
    </form>
</main>