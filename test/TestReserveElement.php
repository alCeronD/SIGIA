<?php 
// Prueba realizada
use PHPUnit\Framework\TestCase;
use App\Config\Conection;
use App\Modules\Elementos\Model\ElementoModelo;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Objetivo del test:
 * validar que un elemento sea reservado desde el modulo de solicitud de prestamos.
 */

class TestReserveElement extends TestCase{
    // Objetivo de tipo mysqli
    public mysqli $conection;
    public ElementoModelo $modelElemento;
    

    // Función de preparación.
    protected function setUp(): void
    {
        $conn = new Conection();
        $this->conection = $conn->getConnect();
        $this->modelElemento = new ElementoModelo();
    }

    public static function getData(){
        return [
        'negativo' => [-3, 2, 'codigo de elemento negativo, no permitido'],
        'vacio'    => [null, null, 'alguno de los datos están vacios'],
        ];
    }

    #[DataProvider('getData')]
    public function testReserve($idCodigo, $estadoCodigo, $expectedMessage){
        $response = $this->modelElemento->actualizarEstadoElemento($idCodigo, $estadoCodigo);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertFalse($response['status'], 'Se esperaba que el estado fuera false');
        $this->assertArrayHasKey('message', $response);
        $this->assertStringContainsString($expectedMessage, $response['message'], 'Mensaje esperado no encontrado');
    }


}


?>