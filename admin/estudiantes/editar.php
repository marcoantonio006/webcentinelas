<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Categoria.php';

// Verificar que llegó un id válido por la URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

// Buscar el estudiante en la BD
$datos = Estudiante::findById($id);

// Si no existe ese id en la BD, redirigir
if (!$datos) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre                 = trim($_POST['nombre'] ?? '');
    $apellido               = trim($_POST['apellido'] ?? '');
    $cedula                 = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento       = trim($_POST['fecha_nacimiento'] ?? '');
    $lugar_nacimiento       = trim($_POST['lugar_nacimiento'] ?? '');
    $nombre_representante   = trim($_POST['nombre_representante'] ?? '');
    $apellido_representante = trim($_POST['apellido_representante'] ?? '');
    $profesion              = trim($_POST['profesion'] ?? '');
    $domicilio              = trim($_POST['domicilio'] ?? '');
    $categoria_id           = trim($_POST['categoria_id'] ?? '');

    if ($nombre === '')       $errores[] = 'El nombre es obligatorio';
    if ($apellido === '')     $errores[] = 'El apellido es obligatorio';
    if ($cedula === '')       $errores[] = 'La cédula es obligatoria';
    if ($categoria_id === '') $errores[] = 'La categoría es obligatoria';

    if (empty($errores)) {
        $estudiante = new Estudiante();

        $estudiante->setId($id);
        $estudiante->setNombre($nombre);
        $estudiante->setApellido($apellido);
        $estudiante->setCedula($cedula);
        $estudiante->setFechaNacimiento($fecha_nacimiento);
        $estudiante->setLugarNacimiento($lugar_nacimiento);
        $estudiante->setNombreRepresentante($nombre_representante);
        $estudiante->setApellidoRepresentante($apellido_representante);
        $estudiante->setProfesion($profesion);
        $estudiante->setDomicilio($domicilio);
        $estudiante->setCategoriaId($categoria_id);

        $estudiante->editar();

        header('Location: /centinela/admin/estudiantes/index.php');
        exit;
    }
}

$categorias = Categoria::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Editar Estudiante</h1>

    <a class="boton" href="/centinela/admin/estudiantes/index.php">← Volver</a>

    <?php if (!empty($errores)) : ?>
        <?php foreach ($errores as $error) : ?>
            <p class="errores"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <div id="errores-estudiante"></div>

    <form class="formulario" method="POST" onsubmit="return validarEstudiante()">

        <fieldset>
            <legend>Datos del estudiante</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? $datos['nombre']); ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido"
                value="<?php echo htmlspecialchars($_POST['apellido'] ?? $datos['apellido']); ?>">

            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula"
                value="<?php echo htmlspecialchars($_POST['cedula'] ?? $datos['cedula']); ?>">

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? $datos['fecha_nacimiento']); ?>">

            <label for="lugar_nacimiento">Lugar de nacimiento:</label>
            <input type="text" id="lugar_nacimiento" name="lugar_nacimiento"
                value="<?php echo htmlspecialchars($_POST['lugar_nacimiento'] ?? $datos['lugar_nacimiento']); ?>">

            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id">
                <option value="">-- Selecciona una categoría --</option>
                <?php while ($categoria = $categorias->fetch_assoc()) : ?>
                    <option value="<?php echo $categoria['id']; ?>"
                        <?php echo (($_POST['categoria_id'] ?? $datos['categoria_id']) == $categoria['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </fieldset>

        <fieldset>
            <legend>Datos del representante</legend>

            <label for="nombre_representante">Nombre:</label>
            <input type="text" id="nombre_representante" name="nombre_representante"
                value="<?php echo htmlspecialchars($_POST['nombre_representante'] ?? $datos['nombre_representante']); ?>">

            <label for="apellido_representante">Apellido:</label>
            <input type="text" id="apellido_representante" name="apellido_representante"
                value="<?php echo htmlspecialchars($_POST['apellido_representante'] ?? $datos['apellido_representante']); ?>">

            <label for="profesion">Profesión:</label>
            <input type="text" id="profesion" name="profesion"
                value="<?php echo htmlspecialchars($_POST['profesion'] ?? $datos['profesion']); ?>">

            <label for="domicilio">Domicilio:</label>
            <input type="text" id="domicilio" name="domicilio"
                value="<?php echo htmlspecialchars($_POST['domicilio'] ?? $datos['domicilio']); ?>">
        </fieldset>

        <button class="boton" type="submit">Guardar cambios</button>
    </form>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>