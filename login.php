<?php

require_once ('admin/DB.php');

$errores = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* echo "<pre>";
    var_dump($_POST);
    echo "</pre>"; */

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    if($correo === ''){
        $errores[] = "El correo es obligatorio";
    }

    if($password === ''){
        $errores[] = "Ingrese la contraseña por favor";
    }

    if(empty($errores)){

        $conn = DB::conectar();
        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql); 
        $datos = [
            $correo
        ];
        $tiposDatos = "s";
        $stmt->bind_param($tiposDatos, ...$datos);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if($resultado->num_rows){
            $usuario = $resultado->fetch_assoc();

            $auth = password_verify($password, $usuario['password']);

            if($auth){
                session_start();
                $_SESSION['usuario'] = $usuario['correo'];
                $_SESSION['login'] = true;

                header('Location: admin/index.php');
            } else{
                $errores[] = "La contraseña es incorrecta";
            }


        } else{
            $errores[] = "El usuario no existe";
        }
    }
}

include 'templates/header.php';



?>

    <main class="contenedor seccion">
        <h1>Iniciar Sesión</h1>

        <form class="formulario" method="POST">
            <fieldset>
                <legend>Datos de Usuario</legend>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@ejemplo.com" >

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" >

            </fieldset>

            <button class="boton sombra" type="submit">Iniciar Sesión</button>
        </form>
    </main>

<?php

include 'templates/footer.php';

?>