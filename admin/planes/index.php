<?php

session_start();

$auth = $_SESSION['login'] ?? false;

if (!$auth) {
    header('Location: /centinela/index.php');
    exit;
}

require_once __DIR__ . '/../../src/PlanPagos.php';
require_once __DIR__ . '/../../src/MetodoPago.php';
require_once __DIR__ . '/../../src/CSRF.php';

$planes       = PlanPagos::listar();
$metodos      = MetodoPago::listar(); 

include __DIR__ . '/../../templates/header.php';
?>

<main class="contenedor seccion">
    <h1>Pagos y Métodos de Pago</h1>

    <a class="boton" href="/centinela/admin/index.php">← Volver</a>

    <section>
        <h2>Pagos</h2>
        <a class="boton" href="/centinela/admin/planes/crear.php">
            <i class="fa-solid fa-plus"></i> Pagos
        </a>

        <?php if ($planes->num_rows === 0) : ?>
            <p>No hay pagos especificados aun.</p>
        <?php else : ?>
            <div class="tabla-contenedor">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($plan = $planes->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($plan['nombre']); ?></td>
                                <td>$<?php echo number_format($plan['precio'], 2); ?></td>
                                <td class="acciones">
                                    <a class="boton"
                                        href="/centinela/admin/planes/editar.php?id=<?php echo $plan['id']; ?>">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </a>
                                    <form method="POST" action="/centinela/admin/planes/eliminar.php">
                                        <?php echo CSRF::campo(); ?>
                                        <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">
                                        <button class="boton boton-rojo" type="submit">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section style="margin-top: 4rem;">
        <h2>Métodos de Pago</h2>
        <a class="boton" href="/centinela/admin/planes/crear_metodo.php">
            <i class="fa-solid fa-plus"></i> Nuevo método
        </a>

        <?php if ($metodos->num_rows === 0) : ?>
            <p>No hay métodos de pago registrados aún.</p>
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
                        <?php while ($metodo = $metodos->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($metodo['nombre']); ?></td>
                                <td class="col-oculta-movil"><?php echo htmlspecialchars($metodo['descripcion'] ?? '—'); ?></td>
                                <td class="acciones">
                                    <a class="boton"
                                        href="/centinela/admin/planes/editar_metodo.php?id=<?php echo $metodo['id']; ?>">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </a>
                                    <form method="POST" action="/centinela/admin/planes/eliminar_metodo.php">
                                        <?php echo CSRF::campo(); ?>
                                        <input type="hidden" name="id" value="<?php echo $metodo['id']; ?>">
                                        <button class="boton boton-rojo" type="submit">
                                            <i class="fa-solid fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
