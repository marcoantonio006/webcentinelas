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

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    
    $nombre = trim($_POST['nombre']           ?? '');
    $apellido = trim($_POST['apellido']         ?? '');
    $cedula = trim($_POST['cedula']           ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $lugar_nacimiento = trim($_POST['lugar_nacimiento'] ?? '');
    $categoria_id = trim($_POST['categoria_id']     ?? '');

    
    $rep_nombre = trim($_POST['rep_nombre']    ?? '');
    $rep_apellido = trim($_POST['rep_apellido']  ?? '');
    $rep_cedula = trim($_POST['rep_cedula']    ?? '');
    $rep_profesion = trim($_POST['rep_profesion'] ?? '');
    $rep_domicilio = trim($_POST['rep_domicilio'] ?? '');

    
    if ($nombre === '') $errores[] = 'El nombre es obligatorio';
    if ($apellido === '') $errores[] = 'El apellido es obligatorio';
    if ($cedula === '') $errores[] = 'La cédula es obligatoria';
    if ($fecha_nacimiento === '') $errores[] = 'La fecha de nacimiento es obligatoria';
    if ($lugar_nacimiento === '') $errores[] = 'El lugar de nacimiento es obligatorio';
    if ($categoria_id === '') $errores[] = 'La categoría es obligatoria';

    
    if ($rep_nombre === '') $errores[] = 'El nombre del representante es obligatorio';
    if ($rep_apellido === '') $errores[] = 'El apellido del representante es obligatorio';
    if ($rep_cedula === '') $errores[] = 'La cédula del representante es obligatoria';
    if ($rep_profesion === '') $errores[] = 'La profesión del representante es obligatoria';
    if ($rep_domicilio === '') $errores[] = 'El domicilio del representante es obligatorio';

    if (empty($errores)) {

        $representante = Representante::findByCedula($rep_cedula);

        if ($representante) {
            
            $representante_id = $representante['id'];
        } else {
            
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
            $atleta->setNombre($nombre);
            $atleta->setApellido($apellido);
            $atleta->setCedula($cedula);
            $atleta->setFechaNacimiento($fecha_nacimiento);
            $atleta->setLugarNacimiento($lugar_nacimiento);
            $atleta->setCategoriaId($categoria_id);
            $atleta->setRepresentanteId($representante_id);

            if (!$atleta->guardar()) {
                $errores[] = 'Ya existe un atleta registrado con esa cédula';
            } else {
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
    <h1>Nuevo Atleta</h1>

    <a class="boton" href="/centinela/admin/estudiantes/index.php">← Volver</a>

    <div id="errores-estudiante"></div>

    <?php foreach ($errores as $error) : ?>
        <p class="errores"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form class="formulario" method="POST" onsubmit="return validarEstudiante()">
        <?php echo CSRF::campo(); ?>

        <fieldset>
            <legend>Datos del atleta</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido"
                value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">

            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula"
                value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? ''); ?>">

            <label for="lugar_nacimiento">Lugar de nacimiento:</label>
            <input type="text" id="lugar_nacimiento" name="lugar_nacimiento"
                value="<?php echo htmlspecialchars($_POST['lugar_nacimiento'] ?? ''); ?>">

            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id">
                <option value="">-- Selecciona una categoría --</option>
                <?php while ($categoria = $categorias->fetch_assoc()) : ?>
                    <option value="<?php echo $categoria['id']; ?>"
                        <?php echo (($_POST['categoria_id'] ?? '') == $categoria['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </fieldset>

        <fieldset>
            <legend>Datos del representante</legend>

            <label for="rep_nombre">Nombre:</label>
            <input type="text" id="rep_nombre" name="rep_nombre"
                value="<?php echo htmlspecialchars($_POST['rep_nombre'] ?? ''); ?>">

            <label for="rep_apellido">Apellido:</label>
            <input type="text" id="rep_apellido" name="rep_apellido"
                value="<?php echo htmlspecialchars($_POST['rep_apellido'] ?? ''); ?>">

            <label for="rep_cedula">Cédula:</label>
            <input type="text" id="rep_cedula" name="rep_cedula"
                value="<?php echo htmlspecialchars($_POST['rep_cedula'] ?? ''); ?>">

            <label for="rep_profesion">Profesión:</label>
            <input type="text" id="rep_profesion" name="rep_profesion"
                value="<?php echo htmlspecialchars($_POST['rep_profesion'] ?? ''); ?>">

            <label for="rep_domicilio">Domicilio:</label>
            <input type="text" id="rep_domicilio" name="rep_domicilio"
                value="<?php echo htmlspecialchars($_POST['rep_domicilio'] ?? ''); ?>">
        </fieldset>

        <button class="boton" type="submit">Guardar atleta</button>
    </form>
</main>

