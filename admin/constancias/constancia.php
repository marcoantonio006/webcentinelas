<?php
session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

function txt($str) {
    return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $institucion_destino  = trim($_POST['institucion_destino'] ?? '');
    $nombre_atleta        = strtoupper(trim($_POST['nombre_atleta'] ?? ''));
    $apellido_atleta      = strtoupper(trim($_POST['apellido_atleta'] ?? ''));
    $cedula_atleta        = trim($_POST['cedula_atleta'] ?? '');
    $anio_escolar         = trim($_POST['anio_escolar'] ?? '');
    $seccion              = strtoupper(trim($_POST['seccion'] ?? ''));
    $estado_seleccion     = strtoupper(trim($_POST['estado_seleccion'] ?? ''));
    $categoria            = strtoupper(trim($_POST['categoria'] ?? ''));
    $fecha_inicio_entreno = trim($_POST['fecha_inicio_entreno'] ?? '');
    $fecha_fin_entreno    = trim($_POST['fecha_fin_entreno'] ?? '');
    $nombre_torneo        = strtoupper(trim($_POST['nombre_torneo'] ?? ''));
    $estado_torneo        = strtoupper(trim($_POST['estado_torneo'] ?? ''));
    $fecha_torneo_inicio  = trim($_POST['fecha_torneo_inicio'] ?? '');
    $fecha_torneo_fin_dia = trim($_POST['fecha_torneo_fin_dia'] ?? '');
    $mes_torneo           = strtoupper(trim($_POST['mes_torneo'] ?? ''));
    $nombre_representante = strtoupper(trim($_POST['nombre_representante'] ?? ''));
    $cedula_representante = trim($_POST['cedula_representante'] ?? '');
    $dia_emision          = trim($_POST['dia_emision'] ?? '');
    $mes_emision          = strtoupper(trim($_POST['mes_emision'] ?? ''));
    $anio_emision         = date('Y');
    $nombre_director      = strtoupper(trim($_POST['nombre_director'] ?? ''));
    $cargo_director       = trim($_POST['cargo_director'] ?? '');

    require_once '../../admin/fpdf/fpdf186/fpdf.php';

    class PDF extends FPDF {
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 7);
            $this->SetTextColor(180, 180, 180);
            $this->Cell(0, 5, txt('Este documento no tiene validez sin firma y sello oficial.'), 0, 0, 'C');
        }
    }

    $pdf = new PDF('P', 'mm', array(190.5, 254));
    $pdf->SetMargins(14, 10, 14);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    $lh = 5.0; // line-height párrafos

    // Escribe un campo en Bold+Underline y vuelve a Arial normal
    $bu = function($valor, $vacio = '_____________________') use ($pdf, $lh) {
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Write($lh, txt($valor ?: $vacio));
        $pdf->SetFont('Arial', 'B', 10);
    };

    // Escribe texto fijo en Bold (etiquetas como ATLETA:, SELECCION, etc.)
    $b = function($texto) use ($pdf, $lh) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, txt($texto));
        $pdf->SetFont('Arial', 'B', 10);
    };

    $n_at = ($nombre_atleta && $apellido_atleta) ? "$nombre_atleta $apellido_atleta" : '';

    // ══════════════════════════════════════════════════════════════════════
    // ENCABEZADO (Times — igual que el original)
    // ══════════════════════════════════════════════════════════════════════
    $logo = __DIR__ . '/../../assets/img/icon-asociacionvoleibolzulia.png';
    if (file_exists($logo)) {
        $pdf->Image($logo, 5, 8, 40);
    }

    $pdf->SetFont('Times', 'B', 15);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(35, 9);
    $pdf->Cell(142, 7, txt('ACADEMIA DE VOLEIBOL CLUB CENTINELAS'), 0, 1, 'C');

    $pdf->SetFont('Times', 'I', 9);
    $pdf->SetTextColor(60, 60, 60);
    $pdf->SetX(35);
    $pdf->Cell(142, 4.5, txt('Fundada el 22 de Octubre de 1954'), 0, 1, 'C');
    $pdf->SetX(35);
    $pdf->Cell(142, 4.5, txt('Afiliada a la Federación Venezolana de Voleibol'), 0, 1, 'C');
    $pdf->SetX(35);
    $pdf->Cell(142, 4.5, txt('Registrada en el Instituto Regional de Deportes del Estado Zulia'), 0, 1, 'C');

    // ── A partir de aquí todo en Arial ────────────────────────────────────

    $pdf->Ln(5);

    // ══════════════════════════════════════════════════════════════════════
    // FECHA
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    // Fecha: una sola Cell alineada a la derecha — sin riesgo de salto
    $diaFmt = $dia_emision  ?: '___';
    $mesFmt = $mes_emision  ?: '__________';
    $pdf->Cell(0, $lh, txt("Maracaibo, $diaFmt de $mesFmt $anio_emision"), 0, 1, 'R');
    // Subrayar día, mes y año dibujando líneas debajo de cada campo
    $pdf->SetFont('Arial', 'B', 10);
    $xRef   = 176;
    $yLine  = $pdf->GetY() - 0.8; // justo bajo la línea anterior
    $wAnio  = $pdf->GetStringWidth($anio_emision);
    $wEsp   = $pdf->GetStringWidth(' ');
    $wMes   = $pdf->GetStringWidth($mesFmt);
    $wDe    = $pdf->GetStringWidth(' de ');
    $wDia   = $pdf->GetStringWidth($diaFmt);
    // subrayado año
    $x1 = $xRef - $wAnio;
    $pdf->SetLineWidth(0.4); $pdf->SetDrawColor(0,0,0);
    $pdf->Line($x1, $yLine, $xRef, $yLine);
    // subrayado mes
    $x2e = $x1 - $wEsp;
    $x2  = $x2e - $wMes;
    $pdf->Line($x2, $yLine, $x2e, $yLine);
    // subrayado día
    $x3e = $x2 - $wDe;
    $x3  = $x3e - $wDia;
    $pdf->Line($x3, $yLine, $x3e, $yLine);
    $pdf->Ln(3);

    // ══════════════════════════════════════════════════════════════════════
    // DESTINATARIO
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, 'LCDO (A): ');
    $bu($institucion_destino, '___________________________________');
    $pdf->Ln($lh);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, 'DIRECTOR (A)');
    $pdf->Ln($lh);
    $pdf->Write($lh, 'SU DESPACHO');
    $pdf->Ln(5);

    // ══════════════════════════════════════════════════════════════════════
    // TÍTULO
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(0, 7, 'CONSTANCIA', 0, 1, 'C');
    $pdf->Ln(2);

    // ══════════════════════════════════════════════════════════════════════
    // PÁRRAFO 1 — flujo continuo sin Ln() en medio
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, txt('        Muy respetados senores, reciban un cordial saludo de parte de la '));
    $b('ACADEMIA DE VOLEIBOL CLUB CENTINELAS');
    $pdf->Write($lh, txt(', a la vez el mejor deseo de exito en el ambiente laboral; La presente tiene la finalidad de solicitarle el permiso para el (la), '));
    $b('ATLETA: ');
    $bu($n_at, '______________________________');
    $pdf->Write($lh, txt(' portador de la cedula de identidad No.: V.- '));
    $bu($cedula_atleta, 'V-______________');
    $pdf->Write($lh, txt(' quien es estudiante de '));
    $bu($anio_escolar, '__');
    $pdf->Write($lh, txt(' año, seccion '));
    $bu($seccion, '__');
    $pdf->Write($lh, txt(', en esta institucion, y es integrante de las '));
    $b('SELECCION ');
    $bu($estado_seleccion, '____________________');
    $pdf->Write($lh, txt(' EN LA CATEGORIA '));
    $bu($categoria, '____________________');
    $pdf->Write($lh, txt(', y debe incorporarse a los entrenamientos desde '));
    $bu($fecha_inicio_entreno, '______');
    $pdf->Write($lh, txt(' hasta el '));
    $bu($fecha_fin_entreno, '______');
    $pdf->Write($lh, txt(' de AMBOS INCLUSIVES del presente año; para representarnos en el CAMPEONATO '));
    $b('NACIONAL');
    $pdf->Write($lh, txt(' de VOLEIBOL '));
    $bu($nombre_torneo, '_____________________________');
    $pdf->Write($lh, txt(', A REALIZARSE EN EL '));
    $bu($estado_torneo, '____________________');
    $pdf->Write($lh, txt(' en la fecha del '));
    $bu($fecha_torneo_inicio, '______');
    $pdf->Write($lh, txt(' al '));
    $bu($fecha_torneo_fin_dia, '__');
    $pdf->Write($lh, txt(' de '));
    $bu($mes_torneo, '______________');
    $pdf->Write($lh, txt(' del ' . $anio_emision . '.'));
    $pdf->Ln(5);

    // ══════════════════════════════════════════════════════════════════════
    // PÁRRAFO 2 — flujo continuo sin Ln() en medio
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, txt('Yo, '));
    $bu($nombre_representante, '___________________________');
    $pdf->Write($lh, txt(', mayor de edad, titular de la Cedula de Identidad, V- '));
    $bu($cedula_representante, '__________');
    $pdf->Write($lh, txt(', representante de la '));
    $b('ATLETA: ');
    $bu($n_at, '______________________________');
    $pdf->Write($lh, txt(', titular de la C.I. No.: V '));
    $bu($cedula_atleta, '__________');
    $pdf->Write($lh, txt(', para que asista al CAMPEONATO '));
    $bu($nombre_torneo, '_____________________________');
    $pdf->Write($lh, txt(' QUE SE REALIZARA EN '));
    $bu($estado_torneo, '____________________');
    $pdf->Write($lh, txt(' DE '));
    $bu($fecha_torneo_inicio, '______');
    $pdf->Write($lh, txt(' DE '));
    $bu($fecha_torneo_fin_dia, '__');
    $pdf->Write($lh, txt(' ' . $anio_emision . '.'));
    $pdf->Ln(8);

    // ══════════════════════════════════════════════════════════════════════
    // FIRMA DEL REPRESENTANTE DEL ATLETA
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetLineWidth(0.4);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Line(14, $pdf->GetY(), 80, $pdf->GetY());
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, txt('Firma Del Representante'));
    $pdf->Ln(5);

    // ══════════════════════════════════════════════════════════════════════
    // LÍNEA DE EXPEDICIÓN — flujo continuo
    // ══════════════════════════════════════════════════════════════════════
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Write($lh, txt('Constancia que se expide a peticion de la parte interesada en esta ciudad de Zulia, a los '));
    $bu($dia_emision, '___');
    $pdf->Write($lh, txt(' dia del mes '));
    $bu($mes_emision, '__________');
    $pdf->Write($lh, txt(' del ' . $anio_emision . '.'));
    $pdf->Ln(5);

    // ══════════════════════════════════════════════════════════════════════
    // BASE LEGAL
    // ══════════════════════════════════════════════════════════════════════
    $lhs = 3.8;
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 4.5, txt('En esta solicitud se fundamenta en las normas juridicas contempladas en:'), 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Write($lhs, txt('CONSTITUCIÓN DE LA REPÚBLICA BOLIVARIANA DE VENEZUELA.'));
    $pdf->Ln($lhs);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Write($lhs, txt('Artículo 111. Todas las personas tienen derechos al deporte y a la Recreacion...'));
    $pdf->Ln($lhs);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Write($lhs, txt('LEY ORGÁNICA DEL DEPORTE. ACTIVIDAD FÍSICA Y EDUCACIÓN FÍSICA.'));
    $pdf->Ln($lhs);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->MultiCell(0, $lhs, txt('Artículo 14. Son derechos que aseguran la práctica del Deporte, Actividad Fisico Y la Educación Física de todas las personas:'), 0, 'L');
    $pdf->MultiCell(0, $lhs, txt('Ordinal 5. El de permisos para las (os) Trabajadoras (es) y estudiantes del Sistema Educativo Nacional que sean seleccionados (as) para representar al país. Al Estado o Municipio en competiciones Internacionales, Nacionales, Estadales o Municipales. Dichos permisos no excederen de noventa días: en el caso de los trabajadores (as) seran remunerados.'), 0, 'L');
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Write($lhs, 'CAPITULO II');
    $pdf->Ln($lhs);
    $pdf->Write($lhs, txt('De las violaciones a la Ley'));
    $pdf->Ln($lhs);
    $pdf->Write($lhs, 'INFRACCIONES A LA PRESENTE LEY');
    $pdf->Ln($lhs);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->MultiCell(0, $lhs, txt('Artículo 79. Se la consideran faltas a la presente LEY SANCIONABLES CON MULTAS ENTRE MIL UNIDADES TRIBUTARIAS (1.000 U.T) Y TRES MIL QUINIENTAS UNIDADES TRIBUTARIAS (3.500 U.T)'), 0, 'L');
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->MultiCell(152, $lhs, txt('1.  NEGARSE A CONCEDER EL PERMISO A LOS ESTUDIANTES O TRABAJADORES Y TRABAJADORAS PARA SU PREPARACION Y PARTICIPACION EN EVENTOS COMPETITIVOS.'), 0, 'L');

    $pdf->Ln(2);

    // ══════════════════════════════════════════════════════════════════════
    // FIRMAS FINALES
    // ══════════════════════════════════════════════════════════════════════
    $yF = $pdf->GetY();

    $pdf->SetLineWidth(0.4);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Line(14, $yF + 8, 94, $yF + 8);
    $pdf->SetXY(14, $yF + 10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(80, 5, txt($nombre_director ?: 'NOMBRE DEL DIRECTOR'), 0, 1, 'L');
    $pdf->SetX(14);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, 5, txt($cargo_director ?: 'CARGO / TITULO'), 0, 1, 'L');
    $pdf->SetX(14);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(80, 5, txt('ACADEMIA DE VOLEIBOL CLUB CENTINELAS'), 0, 1, 'L');

    $pdf->SetDrawColor(150, 150, 150);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(112, $yF, 60, 24);
    $pdf->SetXY(112, $yF + 8);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell(60, 5, txt('Sello de la institucion'), 0, 1, 'C');

    $nombreArchivo = 'permiso_' . $nombre_atleta . '_' . $apellido_atleta . '.pdf';
    $pdf->Output('I', $nombreArchivo);
    exit;
}

include '../../templates/header.php';
?>

<main class="contenedor seccion">

    <a class="boton" href="../index.php">&larr; Volver</a>
    <h1>Crear Permiso / Constancia</h1>

    <form class="formulario" action="create.php" method="POST">

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

            <label for="fecha_torneo_fin_dia">Día fin del torneo:</label>
            <input type="text" id="fecha_torneo_fin_dia" name="fecha_torneo_fin_dia" value="07">

            <label for="mes_torneo">Mes fin del torneo:</label>
            <input type="text" id="mes_torneo" name="mes_torneo" value="MAYO">
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