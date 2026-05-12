<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Estudiante.php';
require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

$estudiantes = Estudiante::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Panel de Atletas</h1>

    <a class="boton" href="/centinela/admin/estudiantes/crear.php">
        <i class="fa-solid fa-plus"></i> Nuevo atleta
    </a>
    <a class="boton" href="/centinela/admin/categorias/index.php">
        <i class="fa-solid fa-tags"></i> Administrar categorías
    </a>

    <?php if ($estudiantes->num_rows === 0) : ?>
        <p>No hay atletas registrados aún.</p>
    <?php else : ?>

        
        <div class="filtro-contenedor">
            <label class="filtrar-categorias" for="filtro-categoria">Filtrar por categoría:</label>
            <select class="filtrar-categorias"  id="filtro-categoria">
                <option class="filtro-categoria" value="">Todas las categorías</option>
                <?php
                $categoriasSelect = Categoria::listar();
                while ($cat = $categoriasSelect->fetch_assoc()) :
                ?>
                    <option value="<?php echo htmlspecialchars($cat['nombre']); ?>">
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

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
                        <tr data-categoria="<?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? ''); ?>">
                            <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? '—'); ?></td>
                            <td class="acciones">
                                <button class="boton boton-info" onclick="abrirModal(
                                    '<?php echo htmlspecialchars($estudiante['nombre']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['apellido']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['cedula']); ?>',
                                    '<?php echo htmlspecialchars($estudiante['telefono'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['correo'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['fecha_nacimiento'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['lugar_nacimiento'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_nombre'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_apellido'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_cedula'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_telefono'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_correo'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_profesion'] ?? '—'); ?>',
                                    '<?php echo htmlspecialchars($estudiante['rep_domicilio'] ?? '—'); ?>'
                                )">
                                    <i class="fa-solid fa-eye"></i> Ver detalles
                                </button>

                                <a class="boton boton-verde"
                                    href="/centinela/admin/constancias/crear_constancia.php?id=<?php echo $estudiante['id']; ?>">
                                    <i class="fa-solid fa-file-pdf"></i> Constancia
                                </a>

                                <a class="boton"
                                    href="/centinela/admin/estudiantes/editar.php?id=<?php echo $estudiante['id']; ?>">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </a>

                                <form method="POST" action="/centinela/admin/estudiantes/eliminar.php">
                                    <?php echo CSRF::campo(); ?>
                                    <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
                                    <button class="boton boton-rojo" type="submit">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>

                            <!-- Dropdown para móvil -->
                            <td class="acciones-dropdown">
                                <button class="acciones-dropdown-btn">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="acciones-dropdown-menu">
                                    <button class="boton boton-info" onclick="abrirModal(
                                        '<?php echo htmlspecialchars($estudiante['nombre']); ?>',
                                        '<?php echo htmlspecialchars($estudiante['apellido']); ?>',
                                        '<?php echo htmlspecialchars($estudiante['cedula']); ?>',
                                        '<?php echo htmlspecialchars($estudiante['telefono'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['correo'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['fecha_nacimiento'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['lugar_nacimiento'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['categoria_nombre'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_nombre'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_apellido'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_cedula'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_telefono'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_correo'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_profesion'] ?? '—'); ?>',
                                        '<?php echo htmlspecialchars($estudiante['rep_domicilio'] ?? '—'); ?>'
                                    )">
                                        <i class="fa-solid fa-eye"></i> Ver detalles
                                    </button>
                                    <a href="/centinela/admin/constancias/crear_constancia.php?id=<?php echo $estudiante['id']; ?>">
                                        <i class="fa-solid fa-file-pdf"></i> Constancia
                                    </a>
                                    <a href="/centinela/admin/estudiantes/editar.php?id=<?php echo $estudiante['id']; ?>">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </a>
                                    <form method="POST" action="/centinela/admin/estudiantes/eliminar.php">
                                        <?php echo CSRF::campo(); ?>
                                        <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
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
    <?php endif; ?>
</main>

<!-- Modal -->
<div id="modal" class="modal-fondo" onclick="cerrarModal(event)">
    <div class="modal-caja">
        <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
        <h2 class="modal-titulo">Detalles del Atleta</h2>

        <div class="modal-grid">
            <div class="modal-seccion">
                <h3>Datos del atleta</h3>
                <p><span>Nombre:</span> <strong id="m-nombre"></strong></p>
                <p><span>Apellido:</span> <strong id="m-apellido"></strong></p>
                <p><span>Cédula:</span> <strong id="m-cedula"></strong></p>
                <p><span>Teléfono:</span> <strong id="m-telefono"></strong></p>
                <p><span>Correo:</span> <strong id="m-correo"></strong></p>
                <p><span>Fecha de nacimiento:</span> <strong id="m-fecha"></strong></p>
                <p><span>Lugar de nacimiento:</span> <strong id="m-lugar"></strong></p>
                <p><span>Categoría:</span> <strong id="m-categoria"></strong></p>
            </div>

            <div class="modal-seccion">
                <h3>Datos del representante</h3>
                <p><span>Nombre:</span> <strong id="m-rep-nombre"></strong></p>
                <p><span>Apellido:</span> <strong id="m-rep-apellido"></strong></p>
                <p><span>Cédula:</span> <strong id="m-rep-cedula"></strong></p>
                <p><span>Teléfono:</span> <strong id="m-rep-telefono"></strong></p>
                <p><span>Correo:</span> <strong id="m-rep-correo"></strong></p>
                <p><span>Profesión:</span> <strong id="m-rep-profesion"></strong></p>
                <p><span>Domicilio:</span> <strong id="m-rep-domicilio"></strong></p>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModal(
    nombre, apellido, cedula, telefono, correo, fecha, lugar, categoria,
    repNombre, repApellido, repCedula, repTelefono, repCorreo, repProfesion, repDomicilio
) {
    document.getElementById('m-nombre').textContent    = nombre;
    document.getElementById('m-apellido').textContent  = apellido;
    document.getElementById('m-cedula').textContent    = cedula;
    document.getElementById('m-telefono').textContent  = telefono;
    document.getElementById('m-correo').textContent    = correo;
    document.getElementById('m-fecha').textContent     = fecha;
    document.getElementById('m-lugar').textContent     = lugar;
    document.getElementById('m-categoria').textContent = categoria;
    document.getElementById('m-rep-nombre').textContent    = repNombre;
    document.getElementById('m-rep-apellido').textContent  = repApellido;
    document.getElementById('m-rep-cedula').textContent    = repCedula;
    document.getElementById('m-rep-telefono').textContent  = repTelefono;
    document.getElementById('m-rep-correo').textContent    = repCorreo;
    document.getElementById('m-rep-profesion').textContent = repProfesion;
    document.getElementById('m-rep-domicilio').textContent = repDomicilio;

    document.getElementById('modal').classList.add('modal-visible');
}

function cerrarModal(event) {
    if (!event || event.target === document.getElementById('modal')) {
        document.getElementById('modal').classList.remove('modal-visible');
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarModal();
});
</script>
