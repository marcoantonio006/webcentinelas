<?php
session_start();
$auth = $_SESSION['login'] ?? false;
if (!$auth) { header('Location: /centinela/index.php'); exit; }

require_once __DIR__ . '/../../src/HistorialConstancia.php';
require_once __DIR__ . '/../../src/CSRF.php';

$registros = HistorialConstancia::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Historial de Constancias</h1>
    <a class="boton" href="/centinela/admin/index.php">← Volver</a>

    <?php if ($registros->num_rows === 0): ?>
        <p>No se han generado constancias aún.</p>
    <?php else: ?>

        <div class="tabla-contenedor">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Atleta</th>
                        <th class="col-oculta-movil">Torneo</th>
                        <th class="col-oculta-movil">Generada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    $registros->data_seek(0);
                    while ($reg = $registros->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['nombre_atleta']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($reg['nombre_torneo']); ?></td>
                            <td class="col-oculta-movil">
                                <?php echo date('d/m/Y H:i', strtotime($reg['generada_en'])); ?>
                            </td>
                            <td class="acciones">
                                <button class="boton boton-info"
                                    onclick="abrirModalConstancia(<?php echo $reg['id']; ?>)">
                                    <i class="fa-solid fa-eye"></i> Ver detalles
                                </button>
                                <form method="POST" action="/centinela/admin/constancias/eliminar_historial.php">
                                    <?php echo CSRF::campo(); ?>
                                    <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                                    <button class="boton boton-rojo" type="submit">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                            <td class="acciones-dropdown">
                                <button class="acciones-dropdown-btn">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="acciones-dropdown-menu">
                                    <button onclick="abrirModalConstancia(<?php echo $reg['id']; ?>)">
                                        <i class="fa-solid fa-eye"></i> Ver detalles
                                    </button>
                                    <form method="POST" action="/centinela/admin/constancias/eliminar_historial.php">
                                        <?php echo CSRF::campo(); ?>
                                        <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                                        <button class="boton-rojo" type="submit">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        
        <script>
            const constanciasData = {};
            <?php
            
            $registros->data_seek(0);
            while ($reg = $registros->fetch_assoc()):
                $d = json_decode($reg['datos_json'], true) ?? [];
            ?>
            constanciasData[<?php echo $reg['id']; ?>] = {
                atleta:        '<?php echo htmlspecialchars(($d['nombre_atleta'] ?? '') . ' ' . ($d['apellido_atleta'] ?? ''), ENT_QUOTES); ?>',
                cedula:        '<?php echo htmlspecialchars($d['cedula_atleta']        ?? '', ENT_QUOTES); ?>',
                torneo:        '<?php echo htmlspecialchars($d['nombre_torneo']        ?? '', ENT_QUOTES); ?>',
                estado:        '<?php echo htmlspecialchars($d['estado_torneo']        ?? '', ENT_QUOTES); ?>',
                fechaInicio:   '<?php echo htmlspecialchars($d['fecha_torneo_inicio']  ?? '', ENT_QUOTES); ?>',
                fechaFin:      '<?php echo htmlspecialchars($d['fecha_torneo_fin'] ?? '', ENT_QUOTES); ?>',
                institucion:   '<?php echo htmlspecialchars($d['institucion_destino']  ?? '', ENT_QUOTES); ?>',
                anioEscolar:   '<?php echo htmlspecialchars($d['anio_escolar']         ?? '', ENT_QUOTES); ?>',
                seccion:       '<?php echo htmlspecialchars($d['seccion']              ?? '', ENT_QUOTES); ?>',
                categoria:     '<?php echo htmlspecialchars($d['categoria']            ?? '', ENT_QUOTES); ?>',
                representante: '<?php echo htmlspecialchars($d['nombre_representante'] ?? '—', ENT_QUOTES); ?>',
                director:      '<?php echo htmlspecialchars($d['nombre_director']      ?? '', ENT_QUOTES); ?>',
                generada:      '<?php echo date('d/m/Y H:i', strtotime($reg['generada_en'])); ?>'
            };
            <?php endwhile; ?>
        </script>

    <?php endif; ?>
    
</main>


<div id="modal-constancia" class="modal-fondo" onclick="cerrarModalConstancia(event)">
    <div class="modal-caja">
        <button class="modal-cerrar" onclick="cerrarModalConstancia()">✕</button>
        <h2 class="modal-titulo">Detalles de Constancia</h2>

        <div class="modal-grid">
            <div class="modal-seccion">
                <h3>Atleta</h3>
                <p><span>Nombre:</span> <strong id="mc-atleta"></strong></p>
                <p><span>Cédula:</span> <strong id="mc-cedula"></strong></p>
                <p><span>Año escolar:</span> <strong id="mc-anio"></strong></p>
                <p><span>Sección:</span> <strong id="mc-seccion"></strong></p>
                <p><span>Categoría:</span> <strong id="mc-categoria"></strong></p>
                <p><span>Representante:</span> <strong id="mc-representante"></strong></p>
            </div>
            <div class="modal-seccion">
                <h3>Torneo</h3>
                <p><span>Nombre:</span> <strong id="mc-torneo"></strong></p>
                <p><span>Estado:</span> <strong id="mc-estado"></strong></p>
                <p><span>Fechas:</span> <strong id="mc-fechas"></strong></p>
                <p><span>Institución destino:</span> <strong id="mc-institucion"></strong></p>
                <p><span>Director firmante:</span> <strong id="mc-director"></strong></p>
                <p><span>Generada el:</span> <strong id="mc-generada"></strong></p>
            </div>
        </div>
    </div>
</div>

<script>

function abrirModalConstancia(id) {
    const d = constanciasData[id];
    if (!d) return;

    document.getElementById('mc-atleta').textContent       = d.atleta;
    document.getElementById('mc-cedula').textContent       = d.cedula;
    document.getElementById('mc-anio').textContent         = d.anioEscolar;
    document.getElementById('mc-seccion').textContent      = d.seccion;
    document.getElementById('mc-categoria').textContent    = d.categoria;
    document.getElementById('mc-representante').textContent = d.representante;
    document.getElementById('mc-torneo').textContent       = d.torneo;
    document.getElementById('mc-estado').textContent       = d.estado;
    document.getElementById('mc-fechas').textContent       = d.fechaInicio + ' al ' + d.fechaFin;
    document.getElementById('mc-institucion').textContent  = d.institucion;
    document.getElementById('mc-director').textContent     = d.director;
    document.getElementById('mc-generada').textContent     = d.generada;

    document.getElementById('modal-constancia').classList.add('modal-visible');
}

function cerrarModalConstancia(event) {
    if (!event || event.target === document.getElementById('modal-constancia')) {
        document.getElementById('modal-constancia').classList.remove('modal-visible');
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarModalConstancia();
});
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>