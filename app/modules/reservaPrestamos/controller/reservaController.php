<?php 
require_once __DIR__ . '/../../../helpers/session.php';
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

    //Función para agregar la reserva.
    public function setReserva(array $data=[]){

        //Validar usuario. para guardar su rol. y su tipo de prestamo, reserva o solicitud.
        if (($_SESSION['usuario']['rol_id'] == 2) || ($_SESSION['usuario']['rol_id'] == 1)) {
            $pres_rol = $_SESSION['usuario']['rol_id'];
            //Reserva
            $tp_pres = 2;
            //Estado
            $pres_estado = 1;

        }
        if (($_SESSION['usuario']['rol_id'] == 4)) {
            $pres_rol = $_SESSION['usuario']['rol_id'];
            //Solicitud
            $tp_pres = 1;
            //Estado
            $pres_estado = 3;

        }

        $codigosElementos = $data["codigosElementos"];

        unset($data["codigosElementos"]);
        //Transformo los códigos en enteros.
        foreach ($codigosElementos as $key => $value) {
            $codigosElementos[$key] = (int) $value;
        }

        //var_dump($data);

        /**
         * array(
         * "cedula"           => "100002",
         * "areaDestino"      => "centro",
         * "fechaReserva"     => "2025-06-16",
         * "inicio"           => "16:38",
         * "fin"              => "18:38",
         * "fechaDevolucion"  => "2025-06-19",
         * "observaciones"    => "hola"
         *)
         */

         /**
          *  pres_fch_reserva = fechaReserva
          * pres_hor_inicio = inicio
          * pres_hor_fin = fin
          * pres_fch_entrega = fechaDevolucion
          * pres_observacion = observaciones
          * pres_destino = areaDestino 
          *
          */


        //TODO: Usar array_combine para cambiar los nombres de las claves.
        // $newKeys = ['pres_fch_slcitud','pres_fch_reserva','pres_hor_inicio','pres_hor_fin','pres_fch_entrega','pres_observacion','pres_destino','pres_estado','tp_pres','pres_rol'];

        // $newsKeys = ['pres_fch_reserva','pres_hor_inicio','pres_hor_fin','pres_fch_entrega','pres_observacion','pres_destino'];

        // // foreach ($data as $key => $value) {
        // //     var_dump($key);
        // // }
        // array_combine($data,$newsKeys);
        // var_dump($data);

        //Cambiar nombre de la llave.
        $data['pres_fch_reserva'] = $data['fechaReserva'];
        unset($data['fechaReserva']);

        $data['pres_hor_inicio'] = $data['inicio'];
        unset($data['inicio']);

        $data['pres_hor_fin'] = $data['fin'];
        unset($data['fin']);

        $data['pres_fch_entrega'] = $data['fechaDevolucion'];
        unset($data['fechaDevolucion']);

        $data['pres_observacion'] = $data['observaciones'];
        unset($data['observaciones']);

        $data['pres_destino'] = $data['areaDestino'];
        unset($data['areaDestino']);

        $data['pres_estado'] = $pres_estado;

        $data['pres_rol'] = $pres_rol;
        $data['tp_pres'] = $tp_pres;


        $response = $this->model->insertReserva($data,$codigosElementos);

        success('Prestamo exitoso',$response);
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


        

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = file_get_contents("php://input");

        $data = json_decode($input, true);

        $controller->setReserva($data);


    }
    exit();

}
//Por defecto me ejecuta la vista, en caso de que no sea una petición.
$controller->reservaView();