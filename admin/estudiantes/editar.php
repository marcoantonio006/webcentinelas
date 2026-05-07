<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Representante.php';
require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

$datos = Estudiante::findById($id);

if (!$datos) {
    header('Location: /centinela/admin/estudiantes/index.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) {
        die('Petición no válida');
    }

    $nombre           = trim($_POST['nombre']           ?? '');
    $apellido         = trim($_POST['apellido']         ?? '');
    $cedula           = trim($_POST['cedula']           ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $lugar_nacimiento = trim($_POST['lugar_nacimiento'] ?? '');
    $categoria_id     = trim($_POST['categoria_id']     ?? '');

    $rep_nombre    = trim($_POST['rep_nombre']    ?? '');
    $rep_apellido  = trim($_POST['rep_apellido']  ?? '');
    $rep_cedula    = trim($_POST['rep_cedula']    ?? '');
    $rep_profesion = trim($_POST['rep_profesion'] ?? '');
    $rep_domicilio = trim($_POST['rep_domicilio'] ?? '');

    if ($nombre === '')           $errores[] = 'El nombre es obligatorio';
    if ($apellido === '')         $errores[] = 'El apellido es obligatorio';
    if ($cedula === '')           $errores[] = 'La cédula es obligatoria';
    if ($fecha_nacimiento === '') $errores[] = 'La fecha de nacimiento es obligatoria';
    if ($lugar_nacimiento === '') $errores[] = 'El lugar de nacimiento es obligatorio';
    if ($categoria_id === '')     $errores[] = 'La categoría es obligatoria';

    if ($rep_nombre === '')    $errores[] = 'El nombre del representante es obligatorio';
    if ($rep_apellido === '')  $errores[] = 'El apellido del representante es obligatorio';
    if ($rep_cedula === '')    $errores[] = 'La cédula del representante es obligatoria';
    if ($rep_profesion === '') $errores[] = 'La profesión del representante es obligatoria';
    if ($rep_domicilio === '') $errores[] = 'El domicilio del representante es obligatorio';

    if (empty($errores)) {

        // Guardar el representante_id anterior ANTES de cualquier cambio,
        // para poder limpiarlo si el atleta cambia de representante
        $viejo_representante_id = $datos['representante_id'] ?? null;

        $representante_existente = Representante::findByCedula($rep_cedula);

        if ($representante_existente) {
            // BUG CORREGIDO: antes solo se usaba el ID y se ignoraban los
            // cambios de nombre, apellido, etc. Ahora se actualizan los datos.
            $rep = new Representante();
            $rep->setId($representante_existente['id']);
            $rep->setNombre($rep_nombre);
            $rep->setApellido($rep_apellido);
            $rep->setCedula($rep_cedula);
            $rep->setProfesion($rep_profesion);
            $rep->setDomicilio($rep_domicilio);

            if (!$rep->editar()) {
                $errores[] = 'Ya existe otro representante con esa cédula';
            } else {
                $representante_id = $representante_existente['id'];
            }
        } else {
            // No existe: crear uno nuevo
            $rep = new Representante();
            $rep->setNombre($rep_nombre);
            $rep->setApellido($rep_apellido);
            $rep->setCedula($rep_cedula);
            $rep->setProfesion($rep_profesion);
            $rep->setDomicilio($rep_domicilio);

            if (!$rep->guardar()) {
                $errores[] = 'Error al guardar el representante';
            } else {
                $representante_id = $rep->getId();
            }
        }

        if (empty($errores)) {
            $atleta = new Estudiante();
            $atleta->setId($id);
            $atleta->setNombre($nombre);
            $atleta->setApellido($apellido);
            $atleta->setCedula($cedula);
            $atleta->setFechaNacimiento($fecha_nacimiento);
            $atleta->setLugarNacimiento($lugar_nacimiento);
            $atleta->setCategoriaId($categoria_id);
            $atleta->setRepresentanteId($representante_id);

            if (!$atleta->editar()) {
                $errores[] = 'Ya existe otro atleta registrado con esa cédula';
            } else {
                if ($viejo_representante_id && $viejo_representante_id !== $representante_id) {
                    if (Representante::estaHuerfano($viejo_representante_id)) {
                        Representante::eliminar($viejo_representante_id);
                    }
                }

                header('Location: /centinela/admin/estudiantes/index.php');
                exit;
            }
        }
    }
}

$categorias = Categoria::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Editar Atleta</h1>

    <a class="boton" href="/centinela/admin/estudiantes/index.php">← Volver</a>

    <div id="errores-estudiante"></div>

    <?php if (!empty($errores)) : ?>
        <?php foreach ($errores as $error) : ?>
            <p class="errores"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form class="formulario" method="POST" onsubmit="return validarEstudiante()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Datos del atleta</legend>

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

            <label for="rep_nombre">Nombre:</label>
            <input type="text" id="rep_nombre" name="rep_nombre"
                value="<?php echo htmlspecialchars($_POST['rep_nombre'] ?? $datos['rep_nombre']); ?>">

            <label for="rep_apellido">Apellido:</label>
            <input type="text" id="rep_apellido" name="rep_apellido"
                value="<?php echo htmlspecialchars($_POST['rep_apellido'] ?? $datos['rep_apellido']); ?>">

            <label for="rep_cedula">Cédula:</label>
            <input type="text" id="rep_cedula" name="rep_cedula"
                value="<?php echo htmlspecialchars($_POST['rep_cedula'] ?? $datos['rep_cedula']); ?>">

            <label for="rep_profesion">Profesión:</label>
            <input type="text" id="rep_profesion" name="rep_profesion"
                value="<?php echo htmlspecialchars($_POST['rep_profesion'] ?? $datos['rep_profesion']); ?>">

            <label for="rep_domicilio">Domicilio:</label>
            <input type="text" id="rep_domicilio" name="rep_domicilio"
                value="<?php echo htmlspecialchars($_POST['rep_domicilio'] ?? $datos['rep_domicilio']); ?>">
        </fieldset>

        <button class="boton" type="submit">Guardar cambios</button>
    </form>
</main>
