<?php


    if(!isset($_SESSION)){
        session_start();
    }

    $auth = $_SESSION['login'] ?? false;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Club Centinelas</title>
    <link rel="icon" type="image/png" href="/centinela/assets/img/favicon.png">
    <link rel="stylesheet" href="/centinela/assets/styles/normalize.css">
    <link rel="stylesheet" href="/centinela/assets/styles/app.css">
</head>

<body>

    <header class="header <?php echo isset($inicio) ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">

            <div class="barra">
                <a href="/centinela/index.php">
                    <img class="logo-academia" src="/centinela/assets/img/Adobe Express - file.png" alt="Logo de la academia">
                </a>

                <nav class="navegacion">
                    <a href="/centinela/plantilla.php">Plantilla</a>
                    <a href="/centinela/contacto.php">Contacto</a>
                    <a href="/centinela/eventos.php">Eventos</a>
                    <?php if($auth) {echo '<a href="/centinela/cerrar.php">Cerrar sesion</a>';}?>
                </nav>
            </div>

            <?php if (isset($inicio)) {
                echo "<h1 class='titulo'>Academia de Voleibol Club Centinelas</h1>";
            }
            ?>
        </div>
    </header>