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
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="/assets/styles/normalize.css">
    <link rel="stylesheet" href="/assets/styles/app.css">
</head>

<body>

    <header class="header <?php echo isset($inicio) ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">

            <div class="barra">
                <a href="../index.php">
                    <img class="logo-academia" src="/assets/img/Adobe Express - file.png" alt="Logo de la academia">
                </a>

                <nav class="navegacion">
                    <a href="plantilla.php">Plantilla</a>
                    <a href="contacto.php">Contacto</a>
                    <a href="eventos.php">Eventos</a>
                    <?php if($auth) {echo '<a href="cerrar.php">Cerrar sesion</a>';}?>
                </nav>
            </div>

            <?php if (isset($inicio)) {
                echo "<h1 class='titulo'>Academia de Voleibol Club Centinelas</h1>";
            }
            ?>
        </div>
    </header>