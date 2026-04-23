<?php

require_once __DIR__ . '/src/Evento.php';

include 'templates/header.php';

$eventos = Evento::listar();
?>

<main class="contenedor seccion">
    <h1>Proximos Eventos</h1>

    <?php if ($eventos->num_rows === 0) : ?>
        <p>No hay eventos programados aún.</p>
    <?php else : ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Lugar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($evento = $eventos->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($evento['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($evento['lugar']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

