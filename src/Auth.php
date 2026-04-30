<?php

require_once __DIR__ . '/DB.php';

class Auth {

    public static function login(string $correo, string $password){
        $errores = [];

        if ($correo === '') {
            $errores[] = 'El correo es obligatorio';
        }

        if ($password === '') {
            $errores[] = 'Ingrese la contraseña por favor';
        }

        if (!empty($errores)) {
            return $errores;
        }

        $conn = DB::conectar();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado->num_rows) {
            $errores[] = 'El usuario no existe';
            return $errores;
        }

        $usuario = $resultado->fetch_assoc();

        if (!password_verify($password, $usuario['password'])) {
            $errores[] = 'La contraseña es incorrecta';
            return $errores;
        }

        $_SESSION['usuario'] = $usuario['correo'];
        $_SESSION['login']   = true;

        return [];
    }
}