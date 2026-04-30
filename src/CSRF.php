<?php

class CSRF {

    public static function generar() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verificar() {
        $tokenForm    = $_POST['csrf_token'] ?? '';
        $tokenSession = $_SESSION['csrf_token'] ?? '';

        if (empty($tokenForm) || empty($tokenSession)) {
            return false;
        }

        return hash_equals($tokenSession, $tokenForm);
    }

    public static function campo() {
        $token = self::generar();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}