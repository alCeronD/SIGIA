<?php 
// PRUEBA REALIZADA
namespace Tests;
use App\Config\Conection;
use PHPUnit\Framework\TestCase;
// El nombre del archivo debe ser igual al de la clase.
Class TestConection extends TestCase{
    public function testConexionNoEsNull(){
        $conexion = new Conection();
        // $conn = $conexion->getConnect();
        $this->assertNotNull($conexion->getConnect(), 'el valor de la conexión no null');
    }
}


?>