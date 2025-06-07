<?php
include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';

class solicitudPrestamosController{
    
    
    private $conn;
    
    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    public function registrarPrestamosView(){
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        
        //Me traigo el listado de los elementos al front
        // $objetoElemento = new solicitudPrestamos($this->conn);
        // $elementos = $objetoElemento->search();
        
        // $objetoArea = new area();            
        
        
        // $objetoArea = 
        // dd($elementos);

        return include_once __DIR__ . '/../views/registrarPrestamosView.php';
    }
    
    public function registrarPrestamo(){
    
    }
    
    
}

?>