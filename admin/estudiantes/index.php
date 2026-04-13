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

    <a class="boton" href="../index.php">&larr; Volver</a>
    <h1>Estudiantes</h1>

    <a class="boton" href="/centinela/admin/estudiantes/crear.php">Nuevo estudiante</a>

    <?php if ($estudiantes->num_rows === 0) : ?>
        <p>No hay estudiantes registrados aún.</p>
    <?php else : ?>
        <table border="1" class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($estudiante = $estudiantes->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['categoria_nombre']); ?></td>
                        <td>
                            <a class="boton" href="/centinela/admin/estudiantes/editar.php?id=<?php echo $estudiante['id']; ?>">Editar</a>
                            <a class="boton" href="/centinela/admin/estudiantes/eliminar.php?id=<?php echo $estudiante['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
