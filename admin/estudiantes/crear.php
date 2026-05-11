<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Persona.php';
require_once __DIR__ . '/../../src/Representante.php';
require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!CSRF::verificar()) die('Petición no válida');

    $nombre           = trim($_POST['nombre']           ?? '');
    $apellido         = trim($_POST['apellido']         ?? '');
    $cedula           = trim($_POST['cedula']           ?? '');
    $telefono         = trim($_POST['telefono']         ?? '');
    $correo           = trim($_POST['correo']           ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $lugar_nacimiento = trim($_POST['lugar_nacimiento'] ?? '');
    $categoria_id     = trim($_POST['categoria_id']     ?? '');

    $rep_nombre    = trim($_POST['rep_nombre']    ?? '');
    $rep_apellido  = trim($_POST['rep_apellido']  ?? '');
    $rep_cedula    = trim($_POST['rep_cedula']    ?? '');
    $rep_telefono  = trim($_POST['rep_telefono']  ?? '');
    $rep_correo    = trim($_POST['rep_correo']    ?? '');
    $rep_profesion = trim($_POST['rep_profesion'] ?? '');
    $rep_domicilio = trim($_POST['rep_domicilio'] ?? '');

    $esMayor = false;
    if ($fecha_nacimiento !== '') {
        $edad    = (new DateTime())->diff(new DateTime($fecha_nacimiento))->y;
        $esMayor = $edad >= 18;
    }

    $tiene_representante = isset($_POST['tiene_representante']);

    if ($nombre === '')           $errores[] = 'El nombre es obligatorio';
    if ($apellido === '')         $errores[] = 'El apellido es obligatorio';
    if ($cedula === '')           $errores[] = 'La cédula es obligatoria';
    elseif (!preg_match('/^[0-9]{6,9}$/', $cedula))
                                  $errores[] = 'La cédula debe contener entre 6 y 9 dígitos';
    if ($fecha_nacimiento === '') $errores[] = 'La fecha de nacimiento es obligatoria';
    if ($lugar_nacimiento === '') $errores[] = 'El lugar de nacimiento es obligatorio';
    if ($categoria_id === '')     $errores[] = 'La categoría es obligatoria';

    if (!$esMayor || $tiene_representante) {
        if ($rep_nombre === '')    $errores[] = 'El nombre del representante es obligatorio';
        if ($rep_apellido === '')  $errores[] = 'El apellido del representante es obligatorio';
        if ($rep_cedula === '')    $errores[] = 'La cédula del representante es obligatoria';
        elseif (!preg_match('/^[0-9]{6,9}$/', $rep_cedula))
                                   $errores[] = 'La cédula del representante debe contener entre 6 y 9 dígitos';
        elseif ($rep_cedula === $cedula)
                                   $errores[] = 'La cédula del representante no puede ser igual a la del atleta';
        if ($rep_profesion === '') $errores[] = 'La profesión del representante es obligatoria';
        if ($rep_domicilio === '') $errores[] = 'El domicilio del representante es obligatorio';
    }

    if (empty($errores)) {

        $personaAtleta = Persona::findByCedula($cedula);

        if ($personaAtleta) {
            $persona_id = $personaAtleta['id'];

            $atletaExistente = Estudiante::findByPersonaId($persona_id);
            if ($atletaExistente) {
                $errores[] = 'Ya existe un atleta registrado con esa cédula';
            }
        } else {
            $persona = new Persona();
            $persona->setNombre($nombre);
            $persona->setApellido($apellido);
            $persona->setCedula($cedula);
            $persona->setTelefono($telefono);
            $persona->setCorreo($correo);

            if (!$persona->guardar()) {
                $errores[] = 'Ya existe una persona con esa cédula';
            } else {
                $persona_id = $persona->getId();
            }
        }

        $representante_id = null;

        if (empty($errores) && (!$esMayor || $tiene_representante)) {

            $personaRep = Persona::findByCedula($rep_cedula);

            if ($personaRep) {
                $repExistente = Representante::findByPersonaCedula($rep_cedula);

                if ($repExistente) {
                    $representante_id = $repExistente['id'];
                } else {
                    $rep = new Representante();
                    $rep->setPersonaId($personaRep['id']);
                    $rep->setProfesion($rep_profesion);
                    $rep->setDomicilio($rep_domicilio);
                    $rep->guardar();
                    $representante_id = $rep->getId();
                }
            } else {
                $personaNueva = new Persona();
                $personaNueva->setNombre($rep_nombre);
                $personaNueva->setApellido($rep_apellido);
                $personaNueva->setCedula($rep_cedula);
                $personaNueva->setTelefono($rep_telefono);
                $personaNueva->setCorreo($rep_correo);
                $personaNueva->guardar();

                $rep = new Representante();
                $rep->setPersonaId($personaNueva->getId());
                $rep->setProfesion($rep_profesion);
                $rep->setDomicilio($rep_domicilio);
                $rep->guardar();
                $representante_id = $rep->getId();
            }
        }

        if (empty($errores)) {
            $atleta = new Estudiante();
            $atleta->setPersonaId($persona_id);
            $atleta->setCategoriaId($categoria_id);
            $atleta->setRepresentanteId($representante_id);
            $atleta->setFechaNacimiento($fecha_nacimiento);
            $atleta->setLugarNacimiento($lugar_nacimiento);
            $atleta->guardar();

            header('Location: /centinela/admin/estudiantes/index.php');
            exit;
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

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono"
                value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo"
                value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">

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

        <div id="fila-checkbox-representante" style="display:none; margin: 1.5rem 0;">
            <label>
                <input type="checkbox" id="tiene-representante" name="tiene_representante">
                ¿Desea agregar un representante?
            </label>
        </div>

        <fieldset id="fieldset-representante" data-requerido="si">
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

            <label for="rep_telefono">Teléfono:</label>
            <input type="text" id="rep_telefono" name="rep_telefono"
                value="<?php echo htmlspecialchars($_POST['rep_telefono'] ?? ''); ?>">

            <label for="rep_correo">Correo:</label>
            <input type="email" id="rep_correo" name="rep_correo"
                value="<?php echo htmlspecialchars($_POST['rep_correo'] ?? ''); ?>">

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

<?php include __DIR__ . '/../../templates/footer.php'; ?>