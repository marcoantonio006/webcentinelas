<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Constancia.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fecha_torneo_fin_raw   = trim($_POST['fecha_torneo_fin'] ?? '');
    $fecha_torneo_fin_parts = explode('-', $fecha_torneo_fin_raw);

    $datos = [
        'institucion_destino'  => trim($_POST['institucion_destino'] ?? ''),
        'nombre_atleta'        => strtoupper(trim($_POST['nombre_atleta'] ?? '')),
        'apellido_atleta'      => strtoupper(trim($_POST['apellido_atleta'] ?? '')),
        'cedula_atleta'        => trim($_POST['cedula_atleta'] ?? ''),
        'anio_escolar'         => trim($_POST['anio_escolar'] ?? ''),
        'seccion'              => strtoupper(trim($_POST['seccion'] ?? '')),
        'estado_seleccion'     => strtoupper(trim($_POST['estado_seleccion'] ?? '')),
        'categoria'            => strtoupper(trim($_POST['categoria'] ?? '')),
        'fecha_inicio_entreno' => trim($_POST['fecha_inicio_entreno'] ?? ''),
        'fecha_fin_entreno'    => trim($_POST['fecha_fin_entreno'] ?? ''),
        'nombre_torneo'        => strtoupper(trim($_POST['nombre_torneo'] ?? '')),
        'estado_torneo'        => strtoupper(trim($_POST['estado_torneo'] ?? '')),
        'fecha_torneo_inicio'  => trim($_POST['fecha_torneo_inicio'] ?? ''),
        'fecha_torneo_fin_dia' => $fecha_torneo_fin_parts[0] ?? '',
        'mes_torneo'           => strtoupper($fecha_torneo_fin_parts[1] ?? ''),
        'nombre_representante' => strtoupper(trim($_POST['nombre_representante'] ?? '')),
        'cedula_representante' => trim($_POST['cedula_representante'] ?? ''),
        'dia_emision'          => trim($_POST['dia_emision'] ?? ''),
        'mes_emision'          => strtoupper(trim($_POST['mes_emision'] ?? '')),
        'anio_emision'         => date('Y'),
        'nombre_director'      => strtoupper(trim($_POST['nombre_director'] ?? '')),
        'cargo_director'       => trim($_POST['cargo_director'] ?? ''),
    ];

    Constancia::generar($datos);
}

include '../../templates/header.php';
?>

<!-- El formulario HTML queda exactamente igual que antes -->

<main class="contenedor seccion">

    <a class="boton" href="../index.php">&larr; Volver</a>
    <h1>Crear Permiso / Constancia</h1>

    <form class="formulario" action="crear_constancia.php" method="POST">

        <fieldset>
            <legend>Destinatario</legend>
            <label for="institucion_destino">Institución educativa (LCDO / Colegio):</label>
            <input type="text" id="institucion_destino" name="institucion_destino" value="COLEGIO SAN JUDAS TADEO">
        </fieldset>

        <fieldset>
            <legend>Datos del Atleta</legend>

            <label for="nombre_atleta">Nombre:</label>
            <input type="text" id="nombre_atleta" name="nombre_atleta" value="LUIS FERNANDO" required>

            <label for="apellido_atleta">Apellido:</label>
            <input type="text" id="apellido_atleta" name="apellido_atleta" value="GONZALEZ GUTIERREZ" required>

            <label for="cedula_atleta">Cédula del atleta:</label>
            <input type="text" id="cedula_atleta" name="cedula_atleta" value="V-33.388.230">

            <label for="anio_escolar">Año escolar:</label>
            <input type="text" id="anio_escolar" name="anio_escolar" value="3">

            <label for="seccion">Sección:</label>
            <input type="text" id="seccion" name="seccion" value="C">

            <label for="estado_seleccion">Estado / Selección:</label>
            <input type="text" id="estado_seleccion" name="estado_seleccion" value="ZULIA">

            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria">
                <option value="INFANTIL A">Infantil A</option>
                <option value="INFANTIL B" selected>Infantil B</option>
                <option value="CADETE">Cadete</option>
                <option value="JUVENIL">Juvenil</option>
                <option value="ADULTO">Adulto</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>Datos del Torneo</legend>

            <label for="nombre_torneo">Nombre del torneo / campeonato:</label>
            <input type="text" id="nombre_torneo" name="nombre_torneo" value="NACIONAL INFANTIL YARACUY 2025">

            <label for="estado_torneo">Estado donde se realiza:</label>
            <input type="text" id="estado_torneo" name="estado_torneo" value="ESTADO YARACUY">

            <label for="fecha_inicio_entreno">Inicio de entrenamientos (dd-mm):</label>
            <input type="text" id="fecha_inicio_entreno" name="fecha_inicio_entreno" value="27-04">

            <label for="fecha_fin_entreno">Fin de entrenamientos (dd-mm):</label>
            <input type="text" id="fecha_fin_entreno" name="fecha_fin_entreno" value="07-05">

            <label for="fecha_torneo_inicio">Fecha inicio del torneo (dd-mm):</label>
            <input type="text" id="fecha_torneo_inicio" name="fecha_torneo_inicio" value="27-04">

            <label for="fecha_torneo_fin">Fecha fin del torneo (dd-mm):</label>
            <input type="text" id="fecha_torneo_fin" name="fecha_torneo_fin" placeholder="Ej: 07-05">
        </fieldset>

        <fieldset>
            <legend>Datos del Representante del Atleta</legend>

            <label for="nombre_representante">Nombre completo del representante:</label>
            <input type="text" id="nombre_representante" name="nombre_representante" value="LUIS ANGEL GONZALEZ D." required>

            <label for="cedula_representante">Cédula del representante:</label>
            <input type="text" id="cedula_representante" name="cedula_representante" value="V-17.232.652">
        </fieldset>

        <fieldset>
            <legend>Datos de Emisión</legend>

            <label for="dia_emision">Día de emisión:</label>
            <input type="text" id="dia_emision" name="dia_emision" value="21">

            <label for="mes_emision">Mes de emisión:</label>
            <input type="text" id="mes_emision" name="mes_emision" value="ABRIL">
        </fieldset>

        <fieldset>
            <legend>Firmante de la Academia</legend>

            <label for="nombre_director">Nombre del director / presidente:</label>
            <input type="text" id="nombre_director" name="nombre_director" value="LCDO. JOSE MONTIEL ARRIETA. MSc" required>

            <label for="cargo_director">Cargo:</label>
            <input type="text" id="cargo_director" name="cargo_director" value="VICEPRESIDENTE AVEZ">
        </fieldset>

        <button type="submit" class="boton">📄 Generar Permiso PDF</button>
    </form>
</main>

<?php include '../../templates/footer.php'; ?>