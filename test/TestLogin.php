<?php
// PRUEBA REALIZADA
use App\Config\Conection;
use App\Modules\Login\Controller\LoginController;
use PHPUnit\Framework\TestCase;
class TestLogin extends TestCase{
    public LoginController $loginController;
    public Conection $conn;

    // Función para preparar todo antes de ejecutar.
    protected function setUp(): void {
        $this->conn = new Conection();
        $this->loginController = new LoginController($this->conn->getConnect());


        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['docum']= '41231233';
        $_GET['pass']= '41231233';
        // $_POST['docum']='';
        // $_POST['pass']='';
    
    }

    // El nombre de la función siempre debe de iniciar con testNombFunción.
    public function testValidateRequestMethod(){
        $value = $this->loginController->login();
        // valido que lo que me devuelve es un arreglo.
        $this->assertIsArray($value);
        // Valido que el arreglo tenga un estatus
        $this->assertArrayHasKey('status', $value);
        $this->assertArrayHasKey('message', $value);
        // Valido que el mensaje esperado sea el mismo al enviado.
        $this->assertSame("método no permitido",$value['message'],"el mensaje esperado concuerda con su resultado");
        $this->assertSame(false,$value['status'], "Devuelve el boolean adecuado");
    }
}

?>