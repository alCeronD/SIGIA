<?php

// Clase para crear expresiones regulares y validar su resultado
Class Regex{

    public const SOLO_NUMEROS = '/^\d+$/';
    public const SERIE = '/^\d+-\d+$/';

    public static function validarNumeros($value){
        if (preg_match(self::SOLO_NUMEROS,$value)) {
            return true;
        }
    }
}

?>