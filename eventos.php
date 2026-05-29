<?php
require_once __DIR__ . '/src/Evento.php';
include 'templates/header.php';
$eventos = Evento::listar();
?>

<main class="contenedor seccion">
    <h1>Próximos Eventos</h1>

    <?php if ($eventos->num_rows === 0): ?>
        <p>No hay eventos programados aún.</p>
    <?php else: ?>
        <div class="eventos-grid">
            <?php while ($evento = $eventos->fetch_assoc()): ?>
                <div class="evento-card">

                    <?php if ($evento['imagen']): ?>
                        <img class="evento-card-img"
                             src="/centinela/assets/img/eventos/<?php echo htmlspecialchars($evento['imagen']); ?>"
                             alt="<?php echo htmlspecialchars($evento['nombre']); ?>">
                    <?php else: ?>
                        <div class="evento-card-img-placeholder">
                            <i class="fa-solid fa-volleyball"></i>
                        </div>
                    <?php endif; ?>

                    <div class="evento-card-body">
                        <h2 class="evento-card-titulo">
                            <?php echo htmlspecialchars($evento['nombre']); ?>
                        </h2>

                        <?php if ($evento['equipo_local'] && $evento['equipo_visit']): ?>
                            <div class="evento-card-enfrentamiento">
                                <span><?php echo htmlspecialchars($evento['equipo_local']); ?></span>
                                <span class="evento-vs">VS</span>
                                <span><?php echo htmlspecialchars($evento['equipo_visit']); ?></span>
                            </div>
                        <?php endif; ?>

                        <p class="evento-card-meta">
                            <i class="fa-solid fa-calendar-days"></i>
                            <?php echo date('d/m/Y', strtotime($evento['fecha'])); ?>
                            <?php if ($evento['hora']): ?>
                                &nbsp;·&nbsp;
                                <i class="fa-solid fa-clock"></i>
                                <?php echo date('H:i', strtotime($evento['hora'])); ?>
                            <?php endif; ?>
                        </p>

                        <p class="evento-card-meta">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo htmlspecialchars($evento['lugar']); ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>
