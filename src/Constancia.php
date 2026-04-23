<?php

require_once __DIR__ . '/../vendor/autoload.php';

function txt(string $str): string {
    return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
}

class PDF extends FPDF {
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 7);
        $this->SetTextColor(180, 180, 180);
        $this->Cell(0, 5, txt('Este documento no tiene validez sin firma y sello oficial.'), 0, 0, 'C');
    }
}

class Constancia {

    public static function generar(array $datos){
        
        $institucion_destino  = $datos['institucion_destino'];
        $nombre_atleta        = $datos['nombre_atleta'];
        $apellido_atleta      = $datos['apellido_atleta'];
        $cedula_atleta        = $datos['cedula_atleta'];
        $anio_escolar         = $datos['anio_escolar'];
        $seccion              = $datos['seccion'];
        $estado_seleccion     = $datos['estado_seleccion'];
        $categoria            = $datos['categoria'];
        $fecha_inicio_entreno = $datos['fecha_inicio_entreno'];
        $fecha_fin_entreno    = $datos['fecha_fin_entreno'];
        $nombre_torneo        = $datos['nombre_torneo'];
        $estado_torneo        = $datos['estado_torneo'];
        $fecha_torneo_inicio  = $datos['fecha_torneo_inicio'];
        $fecha_torneo_fin_dia = $datos['fecha_torneo_fin_dia'];
        $mes_torneo           = $datos['mes_torneo'];
        $nombre_representante = $datos['nombre_representante'];
        $cedula_representante = $datos['cedula_representante'];
        $dia_emision          = $datos['dia_emision'];
        $mes_emision          = $datos['mes_emision'];
        $anio_emision         = $datos['anio_emision'];
        $nombre_director      = $datos['nombre_director'];
        $cargo_director       = $datos['cargo_director'];

        // ── Configuración del PDF ─────────────────────────────────────────
        $pdf = new PDF('P', 'mm', [190.5, 254]);
        $pdf->SetMargins(14, 10, 14);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        $lh = 5.0;

        $bu = function($valor, $vacio = '_____________________') use ($pdf, $lh) {
            
            $pdf->Write($lh, txt($valor ?: $vacio));
            $pdf->SetFont('Arial', 'B', 10);
        };

        $b = function($texto) use ($pdf, $lh) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Write($lh, txt($texto));
        };

        $n_at = ($nombre_atleta && $apellido_atleta) ? "$nombre_atleta $apellido_atleta" : '';

        // ── Encabezado ────────────────────────────────────────────────────
        $logo = __DIR__ . '/../assets/img/favicon.png';
        if (file_exists($logo)) {
            $pdf->Image($logo, 14, 8, 30);
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
        $pdf->Cell(142, 4.5, txt('Afiliada a la Federacion Venezolana de Voleibol'), 0, 1, 'C');
        $pdf->SetX(35);
        $pdf->Cell(142, 4.5, txt('Registrada en el Instituto Regional de Deportes del Estado Zulia'), 0, 1, 'C');

        $pdf->Ln(5);

        // ── Fecha ─────────────────────────────────────────────────────────
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $diaFmt = $dia_emision ?: '___';
        $mesFmt = $mes_emision ?: '__________';
        $pdf->Cell(0, $lh, txt("Maracaibo, $diaFmt de $mesFmt $anio_emision"), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 10);
        $xRef  = 176;
        $yLine = $pdf->GetY() - 0.8;
        $wAnio = $pdf->GetStringWidth($anio_emision);
        $wEsp  = $pdf->GetStringWidth(' ');
        $wMes  = $pdf->GetStringWidth($mesFmt);
        $wDe   = $pdf->GetStringWidth(' de ');
        $wDia  = $pdf->GetStringWidth($diaFmt);
        $x1 = $xRef - $wAnio;
        
        $pdf->Ln(3);

        // ── Destinatario ──────────────────────────────────────────────────
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, 'LCDO (A): ');
        $bu($institucion_destino, '___________________________________');
        $pdf->Ln($lh);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, 'DIRECTOR (A)');
        $pdf->Ln($lh);
        $pdf->Write($lh, 'SU DESPACHO');
        $pdf->Ln(5);

        // ── Título ────────────────────────────────────────────────────────
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, 'CONSTANCIA', 0, 1, 'C');
        $pdf->Ln(2);

        // ── Párrafo 1 ─────────────────────────────────────────────────────
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, txt('        Muy respetados señores, reciban un cordial saludo de parte de la '));
        $b('ACADEMIA DE VOLEIBOL CLUB CENTINELAS');
        $pdf->Write($lh, txt(', a la vez el mejor deseo de exito en el ambiente laboral; La presente tiene la finalidad de solicitarle el permiso para el (la), '));
        $b('ATLETA: ');
        $bu($n_at, '______________________________');
        $pdf->Write($lh, txt(' portador de la cedula de identidad No.: V.- '));
        $bu($cedula_atleta, 'V-______________');
        $pdf->Write($lh, txt(' quien es estudiante de '));
        $bu($anio_escolar, '__');
        $pdf->Write($lh, txt(' ano, seccion '));
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

        // ── Párrafo 2 ─────────────────────────────────────────────────────
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

        // ── Firma del representante ───────────────────────────────────────
        $pdf->SetLineWidth(0.4);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Line(14, $pdf->GetY(), 80, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, txt('Firma Del Representante'));
        $pdf->Ln(5);

        // ── Línea de expedición ───────────────────────────────────────────
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Write($lh, txt('Constancia que se expide a peticion de la parte interesada en esta ciudad de Caracas, a los '));
        $bu($dia_emision, '___');
        $pdf->Write($lh, txt(' dia del mes '));
        $bu($mes_emision, '__________');
        $pdf->Write($lh, txt(' del ' . $anio_emision . '.'));
        $pdf->Ln(5);

        // ── Base legal ────────────────────────────────────────────────────
        $lhs = 3.8;
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(0, 4.5, txt('En esta solicitud se fundamenta en las normas juridicas contempladas en:'), 0, 1, 'C');
        $pdf->Write($lhs, txt('CONSTITUCION DE LA REPUBLICA BOLIVARIANA DE VENEZUELA.'));
        $pdf->Ln($lhs);
        $pdf->Write($lhs, txt('Articulo 111. Todas las personas tienen derechos al deporte y a la Recreacion...'));
        $pdf->Ln($lhs);
        $pdf->Write($lhs, txt('LEY ORGANICA DEL DEPORTE. ACTIVIDAD FISICA Y EDUCACION FISICA.'));
        $pdf->Ln($lhs);
        $pdf->MultiCell(0, $lhs, txt('Articulo 14. Son derechos que aseguran la practica del Deporte, Actividad Fisico Y la Educacion fisica de todas las personas:'), 0, 'L');
        $pdf->MultiCell(0, $lhs, txt('Ordinal 5. El de permisos para las (os) Trabajadoras (es) y estudiantes del Sistema Educativo Nacional que sean seleccionados (as) para representar al pais. Al Estado o Municipio en competiciones Internacionales, Nacionales, Estadales o Municipales. Dichos permisos no excederen de noventa dias: en el caso de los trabajadores (as) seran remunerados.'), 0, 'L');
        $pdf->Write($lhs, 'CAPITULO II');
        $pdf->Ln($lhs);
        $pdf->Write($lhs, txt('De las violaciones a la Ley'));
        $pdf->Ln($lhs);
        $pdf->Write($lhs, 'INFRACCIONES A LA PRESENTE LEY');
        $pdf->Ln($lhs);
        $pdf->MultiCell(0, $lhs, txt('Articulo 79. Se la consideran faltas a la presente LEY SANCIONABLES CON MULTAS ENTRE MIL UNIDADES TRIBUTARIAS (1.000 U.T) Y TRES MIL QUINIENTAS UNIDADES TRIBUTARIAS (3.500 U.T)'), 0, 'L');
        $pdf->SetX(20);
        $pdf->MultiCell(152, $lhs, txt('1.  NEGARSE A CONCEDER EL PERMISO A LOS ESTUDIANTES O TRABAJADORES Y TRABAJADORAS PARA SU PREPARACION Y PARTICIPACION EN EVENTOS COMPETITIVOS.'), 0, 'L');
        $pdf->Ln(2);

        // ── Firmas finales ────────────────────────────────────────────────
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
        $pdf->Cell(80, 5, txt('ACADEMIA DE VOLEIBOL CLUB CENTINELAS'), 0, 1, 'L');

        $pdf->SetDrawColor(150, 150, 150);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect(112, $yF, 60, 24);
        $pdf->SetXY(112, $yF + 8);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(60, 5, txt('Sello de la institucion'), 0, 1, 'C');

        // ── Generar PDF ───────────────────────────────────────────────────
        $nombreArchivo = 'permiso_' . $nombre_atleta . '_' . $apellido_atleta . '.pdf';
        $pdf->Output('I', $nombreArchivo);
        exit;
    }
}