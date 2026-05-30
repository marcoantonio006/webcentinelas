<?php

$inicio = true;
require_once __DIR__ . '/src/PlanPagos.php';
require_once __DIR__ . '/src/MetodoPago.php';

$planes  = PlanPagos::listar();
$metodos = MetodoPago::listar();
include 'templates/header.php';

?>
        <main class="contenedor">
            <div class="flex">
                <div class="contenido seccion">
                    <h1>Lleva tu juego al siguiente nivel</h1>
                    <p class="parrafo">En la Academia de Voleibol Club Centinelas, nos apasiona formar a la próxima generación de talentos del voleibol. Ya sea que estés buscando dar tus primeros pasos en la cancha o perfeccionar tu técnica competitiva, ofrecemos entrenamientos adaptados a todos los niveles y edades. Nuestro objetivo es fomentar la disciplina, el trabajo en equipo y el amor por el deporte en un ambiente sano y profesional.</p>

                    <h2>¡Contáctanos!</h2>
                    <div class="contactos">
                        <div class="contenedor-botones">
                            <a href="https://wa.me/584127752348" target="_blank" class="btn-social btn-whatsapp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                </svg>
                                Nuestro WhatsApp
                            </a>

                            <a href="https://instagram.com/centinelasvoley/" target="_blank" class="btn-social btn-instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                                Nuestro Instagram
                            </a>
                        </div>
                    </div>

                    <?php if ($planes->num_rows > 0) : ?>
                        <section class="contenedor planes-seccion">
                        <h2>Nuestros Planes</h2>
                        <div class="planes-grid">
                    <?php while ($plan = $planes->fetch_assoc()) : ?>
                        <div class="plan-tarjeta">
                            <h3 class="plan-nombre">
                                <?php echo htmlspecialchars($plan['nombre']); ?>
                            </h3>
                            <p class="plan-precio">
                                $<?php echo number_format($plan['precio'], 2); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if ($metodos->num_rows > 0) : ?>
                <section class="contenedor metodos-seccion">
                    <h2>Métodos de Pago</h2>
                    <div class="metodos-grid">
                        <?php while ($metodo = $metodos->fetch_assoc()) : ?>
                            <div class="metodo-tarjeta">
                                <h3 class="metodo-nombre">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <?php echo htmlspecialchars($metodo['nombre']); ?>
                                </h3>
                                <p class="metodo-descripcion">
                                    <?php echo nl2br(htmlspecialchars($metodo['descripcion'] ?? '')); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php endif; ?>
                </div>

                <div class="seccion">
                    <aside class="aside" style="display: flex; flex-direction: column; align-items: center;">
                        <a href="https://www.instagram.com/centinelasvoley/" target="_blank">
                            <img class="imagenesacademia sombra" src="/centinela/assets/img/imagenacademia.png" alt="Imagen de la academia">
                        </a>
                        <a href="https://www.instagram.com/centinelasvoley/" target="_blank">
                            <img class="imagenesacademia sombra" src="/centinela/assets/img/imagenacademia2.png" alt="Imagen de la academia">
                        </a>
                        <a href="https://www.instagram.com/centinelasvoley/" target="_blank">
                            <img class="imagenesacademia sombra" src="/centinela/assets/img/imagenacademia3.png" alt="Imagen de la academia">
                        </a>
                    </aside>
                </div>
            </div>
                
        </main>

        

<?php include 'templates/footer.php'; ?>