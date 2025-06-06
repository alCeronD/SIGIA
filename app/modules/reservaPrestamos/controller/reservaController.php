<?php 

class ReservaController{

    public function __construct(){

    }

    public function reservaView(){


        return include_once __DIR__ . '/../views/reservaView.php';
    }
}

?>