<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/Categoria.php';
require_once __DIR__ . '/../../src/CSRF.php';

$categorias = Categoria::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Categorías</h1>

    <a class="boton" href="/centinela/admin/estudiantes/index.php">← Volver</a>
    <a class="boton" href="/centinela/admin/categorias/crear.php">+ Nueva categoría</a>
    <?php if (($_GET['error'] ?? '') === 'tiene_atletas') : ?>
        <p class="errores">No puedes eliminar una categoría que tiene atletas asignados.</p>
    <?php endif; ?>

    <?php if ($categorias->num_rows === 0) : ?>
        <p>No hay categorías registradas aún.</p>
    <?php else : ?>
        <div class="tabla-contenedor">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="col-oculta-movil">Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($categoria = $categorias->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                            <td class="col-oculta-movil"><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                            <td class="acciones">
                                <a class="boton" href="/centinela/admin/categorias/editar.php?id=<?php echo $categoria['id']; ?>"><i class="fa-solid fa-pen"></i> Editar</a>

                                <form method="POST" action="/centinela/admin/categorias/eliminar.php">
                                    <?php echo CSRF::campo(); ?>
                                    <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                                    <button class="boton boton-rojo" type="submit"><i class="fa-solid fa-trash"></i> Eliminar</button>
                                </form>
                            </td>
                            <!-- Dropdown para móvil -->
                            <td class="acciones-dropdown">
                                <button class="acciones-dropdown-btn">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="acciones-dropdown-menu">
                                    <a href="/centinela/admin/categorias/editar.php?id=<?php echo $categoria['id']; ?>">Editar</a>
                                    <form method="POST" action="/centinela/admin/categorias/eliminar.php">
                                        <?php echo CSRF::campo(); ?>
                                        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                                        <button class="boton boton-rojo" type="submit">Eliminar</button>
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

<?php include __DIR__ . '/../../templates/footer.php'; ?>
