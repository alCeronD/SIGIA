<?php 

require_once __DIR__ . '/../model/reservaModel.php';
require_once __DIR__ . '/../../../helpers/getUrl.php';

// Recibir la respuesta de la solicitud.
$method = $_SERVER['REQUEST_METHOD'];

class ReservaController{
    private $model;

    public function __construct(){
        $reservaModel = new ReservaModel();
        $this->model = $reservaModel;
        include_once __DIR__ . '/../../../helpers/response.php';
    }

    //Muestra la vista de reserva.
    public function reservaView(){

        return include_once __DIR__ . '/../views/reservaView.php';
    }

    //Función para mandar los elementos devolutivos al javscript.
    public function getElementosDevolutivos(int $pages){
        $data = $this->model->selectElements($pages);
        success('Registros',$data);
    }

    public function getUsers($page){
        $data = $this->model->selectUsers($page);

        if ($data != null) {
            success('Usuarios activos',$data);
        }
    }
}

$controller = new ReservaController();
//Valido si lo que se solicita es una petición ajax.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $case = $_GET['action'] ?? '';
        //valor de la página, por defecto, es la página #1.
        $pages = $_GET['pages'] ?? 1;

        switch ($case) {
            case 'users':
                if (method_exists($controller,'getUsers')) {
                    $controller->getUsers($pages);
                }
                break;

            case 'elements':
                //var_dump($pages);
                if (method_exists($controller,'getElementosDevolutivos')) {
                    $controller->getElementosDevolutivos($pages);
                }
            default:
            //TODO: Retornar un valor no valido.
                # code...
                break;
        }


        

    }elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //aca debe de ejecutarse la función del controlador para agregar el prestamo
        echo 'hello world';
    }
    exit();

}
//Por defecto me ejecuta la vista, en caso de que no sea una petición.
$controller->reservaView();

?>