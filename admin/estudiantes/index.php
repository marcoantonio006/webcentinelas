<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';

$estudiantes = Estudiante::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Estudiantes</h1>

    <a class="boton" href="/centinela/admin/estudiantes/crear.php">+ Nuevo estudiante</a>

    <?php if ($estudiantes->num_rows === 0) : ?>
        <p>No hay estudiantes registrados aún.</p>
    <?php else : ?>

        <div class="tabla-contenedor">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th class="col-oculta-movil">Cédula</th>
                        <th class="col-oculta-movil">Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($estudiante = $estudiantes->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? '—'); ?></td>
                            <td class="acciones">
                                <!-- Botón que abre el modal con los datos de este estudiante -->
                                <button class="boton boton-info" onclick="abrirModal(
                                    '<?php echo htmlspecialchars($estudiante['nombre']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['apellido']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['cedula']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['fecha_nacimiento'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['lugar_nacimiento'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['nombre_representante'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['apellido_representante'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['profesion'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['domicilio'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? '—'); ?>'
                                )">Ver detalles</button>

                                <a class="boton" href="/centinela/admin/estudiantes/editar.php?id=<?php echo $estudiante['id']; ?>">Editar</a>

                                <form method="POST" action="/centinela/admin/estudiantes/eliminar.php"
                                    onsubmit="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars($estudiante['nombre']); ?>?')">
                                    <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
                                    <button class="boton boton-rojo" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<!-- Modal -->
<div id="modal" class="modal-fondo" onclick="cerrarModal(event)">
    <div class="modal-caja">
        <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
        <h2 class="modal-titulo">Detalles del Estudiante</h2>

        <div class="modal-grid">
            <div class="modal-seccion">
                <h3>Datos del estudiante</h3>
                <p><span>Nombre:</span> <strong id="m-nombre"></strong></p>
                <p><span>Apellido:</span> <strong id="m-apellido"></strong></p>
                <p><span>Cédula:</span> <strong id="m-cedula"></strong></p>
                <p><span>Fecha de nacimiento:</span> <strong id="m-fecha"></strong></p>
                <p><span>Lugar de nacimiento:</span> <strong id="m-lugar"></strong></p>
                <p><span>Categoría:</span> <strong id="m-categoria"></strong></p>
            </div>

            <div class="modal-seccion">
                <h3>Datos del representante</h3>
                <p><span>Nombre:</span> <strong id="m-rep-nombre"></strong></p>
                <p><span>Apellido:</span> <strong id="m-rep-apellido"></strong></p>
                <p><span>Profesión:</span> <strong id="m-profesion"></strong></p>
                <p><span>Domicilio:</span> <strong id="m-domicilio"></strong></p>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirModal(nombre, apellido, cedula, fecha, lugar, repNombre, repApellido, profesion, domicilio, categoria) {
        document.getElementById('m-nombre').textContent    = nombre;
        document.getElementById('m-apellido').textContent  = apellido;
        document.getElementById('m-cedula').textContent    = cedula;
        document.getElementById('m-fecha').textContent     = fecha;
        document.getElementById('m-lugar').textContent     = lugar;
        document.getElementById('m-categoria').textContent = categoria;
        document.getElementById('m-rep-nombre').textContent   = repNombre;
        document.getElementById('m-rep-apellido').textContent = repApellido;
        document.getElementById('m-profesion').textContent    = profesion;
        document.getElementById('m-domicilio').textContent    = domicilio;

        document.getElementById('modal').classList.add('modal-visible');
    }

    function cerrarModal(event) {
        // Cierra si se hizo clic en el fondo oscuro o en el botón cerrar
        if (!event || event.target === document.getElementById('modal')) {
            document.getElementById('modal').classList.remove('modal-visible');
        }
    }

    // Cierra el modal con la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarModal();
    });
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>