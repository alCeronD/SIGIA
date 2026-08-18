<?php
class Session
{

    /**
     * Validar que el usuario se encuentre logueado
     *
     * @return void
     */
    public static function validateSession()
    {
        // Se puede transformar en una función.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            Rect::fast('/index.php');
        }

        $rol = $usuario['rol_id'] ?? '';
        $usuario = $_SESSION['usuario'] ?? '';
    }

    public static function getRol()
    {
        self::validateSession();
        return $_SESSION['usuario'] ?? null;
    }

    public static function getUsuario()
    {
        self::validateSession();
        return $_SESSION['usuario'] ?? null;
    }
}
