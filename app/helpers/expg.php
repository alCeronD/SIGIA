<?php 

// Clase para crear expresiones regulares y validar su resultado
class Regex{
    /**
     * Summary of SOLO_NUMEROS
     * @var string Expresión regular para validar que el valor recibido solamente tiene números.
     */
    public const SOLO_NUMEROS = '/^\d+$/';
    /**
     * Summary of SERIE 
     * @var string Expresión regular para validar las series de los elementos.
     */
    public const SERIE = '/^\d+-\d+$/';


    public static function validarNumeros($value){
        if (preg_match(self::SOLO_NUMEROS,$value)) {
            return true;
        }
    }
}

?>