<?php 

class ValidateData{

    public function __construct() {}

    /**
     * Función para validar los campos obligatorios esten diligenciados.
     * @param array $data - array asociativo con la información a validar
     * @param array $obligatorios - array con las keys obligatorias
     * @param array $keysMensaje - Array asociativo con su clave y nombre para devolver el nombre sea correctamente visible para el usuario.
     * @throws \Exception
     * @return array{data: array, message: string, status: bool}
     */
    public static function validarCampos(array $data = [], array $obligatorios = [], array $keysMensaje = []){
        try {

            // Recorro los campos obligatorios para validar si tienen información.
            foreach ($obligatorios as $value) {
                if(!isset($data[$value]) || trim($data[$value]) === ''){
                    $nombreCampo = $keysMensaje[$value] ?? $value;
                    throw new Exception("El campo {$nombreCampo} Es obligatorio");
                }
            }

            return [
                'status'=> true,
                'message'=> "Campos validos", 
                'data'=>[]
            ];

        } catch (Exception $e) {
            return [
                'status'=> false,
                'message'=> $e->getMessage(),
                'data'=>[]
            ];
        }
    }
}


?>