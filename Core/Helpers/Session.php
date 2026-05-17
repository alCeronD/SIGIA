<?php
Class Session{

    /**
     * Validar que el usuario se encuentre logueado
     *
     * @return void
     */
    public static function validateSession(){
        // Se puede transformar en una función.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['usuario'])) {
            Rect::fast('/index.php');
        }

        $usuario = $_SESSION['usuario'];
        $rol = $usuario['rol_id'];

    }

    public static function getRol(){
        self::validateSession();
        return $_SESSION['usuario'] ?? null;
    }

    public static function getUsuario(){
        return $_SESSION['usuario'] ?? null;
    }

}