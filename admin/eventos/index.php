<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}
require_once __DIR__ . '/../../src/CSRF.php';
require_once __DIR__ . '/../../src/Evento.php';

$eventos = Evento::listar();

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Eventos</h1>

    <a class="boton" href="/centinela/admin/eventos/crear.php">+ Nuevo evento</a>

    <?php if ($eventos->num_rows === 0) : ?>
        <p>No hay eventos registrados aún.</p>
    <?php else : ?>
        <div class="tabla-contenedor">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($evento = $eventos->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($evento['nombre']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($evento['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($evento['lugar']); ?></td>
                            <td>
                                <a class="boton" href="/centinela/admin/eventos/editar.php?id=<?php echo $evento['id']; ?>">Editar</a>
                                    <form method="POST" action="/centinela/admin/eventos/eliminar.php">
                                    <?php echo CSRF::campo(); ?>
                                    <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">
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
