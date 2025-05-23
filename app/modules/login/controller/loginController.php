<?php 

/**
 * En este login vamos a definir si es correcto las credenciales de los usuarios digitados o no, si son correctas, debe de redireccionar a dashboard.
 * 
 */

require_once __DIR__ . '/../../../helpers/renderView.php';

class LoginController{
    
    public function __construct(){
        $this->getName();
    }

    public function getName(){
        return 'hello world';
    }
}
?>