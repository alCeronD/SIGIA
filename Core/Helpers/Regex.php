<?php

/**
 * Clase para validar datos recibidos de un formulario
 */
class Regex
{

    public const SOLO_NUMEROS = '/^\d+$/';
    public const SERIE = '/^\d+-\d+$/';
    public const SOLO_LETRAS = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]+$/';

    /**
     * Function para validar si el dato a evaluar es de tipo numerico
     *
     * @param [type] $value
     * @return bool
     */
    public static function validarNumeros($value): bool
    {

        return preg_match(self::SOLO_NUMEROS, $value) === 1;
    }
    public static function validarLetras($value): bool
    {
        return preg_match(self::SOLO_LETRAS, $value) === 1;
    }
}
