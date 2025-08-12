<?php
// PRUEBA REALIZADA
use App\Config\Conection;
use PHPUnit\Framework\TestCase;
use App\Modules\Usuarios\Model\UsuariosModel;
use PHPUnit\Framework\Attributes\DataProvider;

class TestDisabledUser extends TestCase
{
    public $conn;
    public int $dataId;
    public array $datasId;
    public $userModel;

    public static function getDatas(){
        return [
            'Caso #1 no encuentra el id' => [500, 'Id no encontrado en la base de datos'],
            'Caso #2 el id Es negativo' => [-333, 'El id no debe ser negativo']
        ];

    }

    protected function setUp(): void
    {
        $newCon = new Conection();
        $this->conn = $newCon->getConnect();
        
        // Verifica que la conexión se haya establecido
        $this->assertNotNull($this->conn, "La conexión a la base de datos no se estableció correctamente");
        
        $this->userModel = new UsuariosModel();

        // Válido que la clase se ha creado correctamente
        $this->assertInstanceOf(UsuariosModel::class, $this->userModel);

    }

    // Objetivo: validar que el usuario no exista en la base de datos y que no ejecute ningun proceso más allá de un mensaje de error de retorno.
    #[DataProvider('getDatas')]
    public function testDisabledUser(int $userId, String $mensajeEsperado){
            $response = $this->userModel->inhabilitarUsuario($userId);

        // Valido que la respuesta sea un arreglo.
        $this->assertIsArray($response);        
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("message", $response);
        $this->assertFalse($response['status']);
        $this->assertStringContainsString($mensajeEsperado, $response['message'],"El mensaje es el esperado");

    }

}
?>