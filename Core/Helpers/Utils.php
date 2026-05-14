<?php

/**
 * Clase general para instanciar funciones basicas requeridas, se usa en forma static porque usamos autoload para validarla.
 */
Class Utils{
  public static function ajaxGeneral()
  {
      return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

  }

  public static function dd($var)
{
    echo "<pre>";
    print_r($var);
    die();
}

public static function getNameModule(){
    return $_GET['modulo'] ?? null;
}

public static function getModulesNames(){
    return [
        'Categorias',
        'ConfigModules',
        'Dashboard',
        'Elementos',
        'GeneralCrud',
        'Login',
        'Permisos',
        'Reportes',
        'ReservaPrestamos',
        'Roles',
        'Usuarios',
        'SolicitudPrestamos'
    ];
}

}