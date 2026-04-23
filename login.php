<?php

require_once __DIR__ . '/src/Auth.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = $_POST['correo']   ?? '';
    $password = $_POST['password'] ?? '';

    $errores = Auth::login($correo, $password);

    if (empty($errores)) {
        header('Location: admin/index.php');
        exit;
    }
}

include 'templates/header.php';

?>

    <main class="contenedor seccion">
        <h1>Iniciar Sesión</h1>

        <?php if (!empty($errores)) : ?>
            
                <?php foreach ($errores as $error) : ?>
                    <div>
                        <p class="errores"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endforeach; ?>
            
        <?php endif; ?>

        <div id="errores-login"></div>

        <form class="formulario" method="POST" onsubmit="return validarLogin()">
            <fieldset>
                <legend>Datos de Usuario</legend>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@ejemplo.com">

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña">
            </fieldset>

            <button class="boton sombra" type="submit">Iniciar Sesión</button>
        </form>
    </main>
