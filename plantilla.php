<?php

require_once __DIR__ . '/src/Estudiante.php';

include 'templates/header.php';

$estudiantes = Estudiante::listar();
?>

<main class="contenedor seccion">
    <h1>Plantilla</h1>

    <?php if ($estudiantes->num_rows === 0) : ?>
        <p>La plantilla esta vacia.</p>
    <?php else : ?>
        <table border="1" class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($estudiante = $estudiantes->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['categoria_nombre']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include 'templates/footer.php'; ?>