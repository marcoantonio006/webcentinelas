<?php
session_start();
$auth = $_SESSION['login'] ?? false;
if (!$auth) { 
    header('Location: /centinela/index.php'); 
    exit; 
}

require_once __DIR__ . '/../../src/Evento.php';
require_once __DIR__ . '/../../src/CSRF.php';

$id = $_GET['id'] ?? null;
if (!$id) { 
    header('Location: /centinela/admin/eventos/index.php'); 
    exit; 
    }

$datos = Evento::findById($id);
if (!$datos) { 
    header('Location: /centinela/admin/eventos/index.php'); 
    exit; 
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre = trim($_POST['nombre'] ?? '');
    $fecha = trim($_POST['fecha'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $lugar = trim($_POST['lugar'] ?? '');
    $equipo_local = trim($_POST['equipo_local'] ?? '');
    $equipo_visit = trim($_POST['equipo_visit'] ?? '');

    if ($nombre === '') $errores[] = 'El nombre del evento es obligatorio';
    if ($fecha === '') $errores[] = 'La fecha es obligatoria';
    if ($lugar === '') $errores[] = 'El lugar es obligatorio';

    
    $imagenFinal = $datos['imagen']; 

    if (!empty($_FILES['imagen']['name'])) {

        $archivo = $_FILES['imagen'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $maxBytes = 2 * 1024 * 1024;

        if (!in_array($extension, $permitidas)) {
            $errores[] = 'La imagen debe ser JPG, PNG o WEBP';
        } elseif ($archivo['size'] > $maxBytes) {
            $errores[] = 'La imagen no puede superar 2 MB';
        } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'Error al subir la imagen';
        } else {
            $imagenFinal = uniqid('evento_') . '.' . $extension;
        }
    }

    if (empty($errores)) {

        if ($imagenFinal !== $datos['imagen'] && !empty($imagenFinal)) {
             
            $destino = __DIR__ . '/../../assets/img/eventos/' . $imagenFinal;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);

            
            if ($datos['imagen']) {
                $anterior = __DIR__ . '/../../assets/img/eventos/' . $datos['imagen'];
                if (file_exists($anterior)) unlink($anterior);
            }
        }

        $evento = new Evento();
        $evento->setId($id);
        $evento->setNombre($nombre);
        $evento->setFecha($fecha);
        $evento->setHora($hora ?: null);
        $evento->setLugar($lugar);
        $evento->setEquipoLocal($equipo_local ?: null);
        $evento->setEquipoVisit($equipo_visit ?: null);
        $evento->setImagen($imagenFinal);
        $evento->editar();

        header('Location: /centinela/admin/eventos/index.php');
        exit;
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Editar Evento</h1>
    <a class="boton" href="/centinela/admin/eventos/index.php">← Volver</a>

    <?php foreach ($errores as $e): ?>
        <p class="errores"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data"
          onsubmit="return validarEvento()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Información del evento</legend>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? $datos['nombre']); ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha"
                value="<?php echo htmlspecialchars($_POST['fecha'] ?? $datos['fecha']); ?>">

            <label for="hora">Hora (opcional):</label>
            <input type="time" id="hora" name="hora"
                value="<?php echo htmlspecialchars($_POST['hora'] ?? $datos['hora'] ?? ''); ?>">

            <label for="lugar">Lugar:</label>
            <input type="text" id="lugar" name="lugar"
                value="<?php echo htmlspecialchars($_POST['lugar'] ?? $datos['lugar']); ?>">
        </fieldset>

        <fieldset>
            <legend>Enfrentamiento (opcional)</legend>
            <label for="equipo_local">Equipo local:</label>
            <input type="text" id="equipo_local" name="equipo_local"
                value="<?php echo htmlspecialchars($_POST['equipo_local'] ?? $datos['equipo_local'] ?? ''); ?>">

            <label for="equipo_visit">Equipo visitante:</label>
            <input type="text" id="equipo_visit" name="equipo_visit"
                value="<?php echo htmlspecialchars($_POST['equipo_visit'] ?? $datos['equipo_visit'] ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Imagen del evento</legend>

            <?php if ($datos['imagen']): ?>
                <p>Imagen actual:</p>
                <img src="/centinela/assets/img/eventos/<?php echo htmlspecialchars($datos['imagen']); ?>"
                     alt="Imagen del evento" style="max-width:200px; border-radius:8px; margin-bottom:8px; display:block;">
                <p style="font-size:0.85rem; color:#666;">Sube una nueva imagen solo si quieres reemplazarla.</p>
            <?php endif; ?>

            <label for="imagen">Nueva imagen (opcional):</label>
            <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">
        </fieldset>

        <button class="boton" type="submit">Guardar cambios</button>
    </form>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>