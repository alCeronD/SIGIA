<?php 

require_once __DIR__ . '/../model/reservaModel.php';

class ReservaController{

    public function __construct(){

    }

    public function reservaView(){


        return include_once __DIR__ . '/../views/reservaView.php';
    }

    //Función para mandar los elementos devolutivos
    public function getElementosDevolutivos(){
        

    }
    
}

?>