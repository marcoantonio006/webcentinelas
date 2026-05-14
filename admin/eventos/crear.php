<?php
session_start();
$auth = $_SESSION['login'] ?? false;
if (!$auth) { header('Location: /centinela/index.php'); exit; }

require_once __DIR__ . '/../../src/Evento.php';
require_once __DIR__ . '/../../src/CSRF.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre       = trim($_POST['nombre']       ?? '');
    $fecha        = trim($_POST['fecha']        ?? '');
    $hora         = trim($_POST['hora']         ?? '');
    $lugar        = trim($_POST['lugar']        ?? '');
    $equipo_local = trim($_POST['equipo_local'] ?? '');
    $equipo_visit = trim($_POST['equipo_visit'] ?? '');

    // ── Validaciones ──────────────────────────────
    if ($nombre === '') $errores[] = 'El nombre del evento es obligatorio';
    if ($fecha  === '') $errores[] = 'La fecha es obligatoria';
    if ($lugar  === '') $errores[] = 'El lugar es obligatorio';

    // ── Manejo de imagen ──────────────────────────
    $nombreImagen = null;

    if (!empty($_FILES['imagen']['name'])) {

        $archivo   = $_FILES['imagen'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $maxBytes   = 2 * 1024 * 1024; // 2 MB

        if (!in_array($extension, $permitidas)) {
            $errores[] = 'La imagen debe ser JPG, PNG o WEBP';
        } elseif ($archivo['size'] > $maxBytes) {
            $errores[] = 'La imagen no puede superar 2 MB';
        } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'Error al subir la imagen';
        } else {
            // Nombre único para evitar colisiones entre archivos
            $nombreImagen = uniqid('evento_') . '.' . $extension;
        }
    }

    if (empty($errores)) {

        // Mover la imagen a su carpeta definitiva SOLO si no hubo errores
        if ($nombreImagen) {
            $destino = __DIR__ . '/../../assets/img/eventos/' . $nombreImagen;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
        }

        $evento = new Evento();
        $evento->setNombre($nombre);
        $evento->setFecha($fecha);
        $evento->setHora($hora ?: null);
        $evento->setLugar($lugar);
        $evento->setEquipoLocal($equipo_local ?: null);
        $evento->setEquipoVisit($equipo_visit ?: null);
        $evento->setImagen($nombreImagen);
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

    <div id="errores-evento"></div>
    <?php foreach ($errores as $e): ?>
        <p class="errores"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>

    <!-- enctype es OBLIGATORIO para subir archivos -->
    <form class="formulario" method="POST" enctype="multipart/form-data"
          onsubmit="return validarEvento()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Información del evento</legend>

            <label for="nombre">Nombre del evento:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha"
                value="<?php echo htmlspecialchars($_POST['fecha'] ?? ''); ?>">

            <label for="hora">Hora (opcional):</label>
            <input type="time" id="hora" name="hora"
                value="<?php echo htmlspecialchars($_POST['hora'] ?? ''); ?>">

            <label for="lugar">Lugar:</label>
            <input type="text" id="lugar" name="lugar"
                value="<?php echo htmlspecialchars($_POST['lugar'] ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Enfrentamiento (opcional)</legend>

            <label for="equipo_local">Equipo local / Selección A:</label>
            <input type="text" id="equipo_local" name="equipo_local"
                value="<?php echo htmlspecialchars($_POST['equipo_local'] ?? ''); ?>">

            <label for="equipo_visit">Equipo visitante / Selección B:</label>
            <input type="text" id="equipo_visit" name="equipo_visit"
                value="<?php echo htmlspecialchars($_POST['equipo_visit'] ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Imagen del evento (opcional)</legend>

            <label for="imagen">Imagen (JPG, PNG o WEBP, máx. 2 MB):</label>
            <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">
        </fieldset>

        <button class="boton" type="submit">Guardar evento</button>
    </form>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>