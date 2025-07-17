<?php
require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../model/reservaModel.php';
require_once __DIR__ . '/../../../helpers/getUrl.php';


// Recibir la respuesta de la solicitud.
$method = $_SERVER['REQUEST_METHOD'];

class ReservaController
{
    private $model;

    public function __construct()
    {
        $reservaModel = new ReservaModel();
        $this->model = $reservaModel;
        include_once __DIR__ . '/../../../helpers/response.php';
    }

    //Muestra la vista de reserva formulario.
    public function reservaView()
    {

        return include_once __DIR__ . '/../views/reservaView.php';
    }

    public function consultaReservaView()
    {

        return include_once __DIR__ . '/../views/consultarReservaView.php';
    }

    public function getElementosDevolutivos(int $pages)
    {
        $data = $this->model->selectElements($pages);
        success('Registros', $data);
    }

    public function getElementosConsumibles(int $pages = 1, int $type = 2)
    {
        $data = $this->model->selectElements($pages, $type);
        success('elementos consumibles', $data);
    }

    public function getUsers($page)
    {
        $data = $this->model->selectUsers($page);

        if ($data != null) {
            success('Usuarios activos', $data);
        }
    }

    /**
     * Procesa y estructura los datos de una solicitud o reserva de préstamo,
     * transformándolos al formato requerido por la base de datos antes de ser insertados.
     * 
     * Determina el tipo de préstamo y estado según el rol del usuario actual, 
     * organiza y limpia los datos, y luego delega la inserción al método insertReserva().
     *
     * @param array $data Datos del formulario recibidos desde el cliente. Espera una estructura con:
     *  - 'cedula'                => string|int   - Documento del usuario solicitante.
     *  - 'fechaReserva'          => string       - Fecha de inicio de la reserva (ISO 8601).
     *  - 'fechaDevolucion'       => string       - Fecha de devolución esperada (ISO 8601).
     *  - 'inicio'                => string|null  - Hora de inicio (si aplica).
     *  - 'fin'                   => string|null  - Hora de fin (si aplica).
     *  - 'observaciones'         => string       - Observaciones del usuario.
     *  - 'areaDestino'           => string       - Área de destino ('centro', 'externo', etc).
     *  - 'codigosElementos'      => array        - Lista de elementos separados por tipo:
     *       - 'consumibles' => [ ['codigo' => int, 'cantidad' => int], ... ]
     *       - 'devolutivos' => [ ['codigo' => int, 'cantidad' => int], ... ]
     *
     * @return void
     *
     * @throws void En caso de error, la función llama a `fail()` con el mensaje correspondiente.
     *              Si tiene éxito, llama a `success()` con la confirmación.
     */
    public function setReserva(array $data = [])
    {
        //Validar usuario. para guardar su rol. y su tipo de prestamo, reserva o solicitud.
        if (($_SESSION['usuario']['rol_id'] == 2) || ($_SESSION['usuario']['rol_id'] == 1)) {
            $pres_rol = $_SESSION['usuario']['rol_id'];
            //Reserva
            $tp_pres = 1;
            //Estado
            $pres_estado = 1;
        }
        if (($_SESSION['usuario']['rol_id'] == 4)) {
            $pres_rol = $_SESSION['usuario']['rol_id'];
            //Solicitud
            $tp_pres = 2;
            //Estado
            $pres_estado = 3;
        }

        $codConsumibles = $data["codigosElementos"]['consumibles'];
        $codDevolu = $data["codigosElementos"]['devolutivos'];

        $ascDevolutivos = array_column($codDevolu, 'codigo');
        $ascConsu = array_column($codConsumibles, 'codigo');
        //Cordenar los elementos del arreglo basado en el código
        array_multisort($codDevolu, SORT_ASC, $ascDevolutivos);
        array_multisort($codConsumibles, SORT_ASC, $ascConsu);

        unset($data["codigosElementos"]);

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
        $result = $this->model->insertReserva($data, $codDevolu, $codConsumibles);
        if (!$result) {
            fail($result['message']);
        } else {
            success($result['message']);
        }
    }

    //Función para validar la solicitud del aprendiz/instructor y cambiar su estado a validado
    public function setSolicitud(array $data = [])
    {
        $cedula = $data['dataUsuario']['nroIdentidad'];

        $result = $this->model->validateSolicitud($data, $cedula);
        if (!$result) {
            fail('error al validar el prestamo');
        }
        success('prestamo validado');
    }

    //Finalizar la reserva, es decir, cuando el usuario devuelve los elementos.
    public function setEndReserva(array $elementos = [], int $codigo = 0)
    {
        // $data = $this->model->endReserva($elementos,$codigo);
        $data = $this->model->endReserva($elementos, $codigo);
        if ($data['status']) {
            success('Prestamo exitoso');
        }
    }

    //Función para traer las reservas
    public function getReservas(int $pages = 0, String $type = '')
    {
        if (!$pages) {
            fail('pagina no definida');
        }

        // Puedo guardar los estados en un arreglo y validarlo con la clave del arreglo que me recibe.
        $estadoPrestamo =
            $type === 'all'       ? 0 :
            ($type === 'validate'   ? 1 :
            ($type === 'Rechazado'  ? 2 :
            ($type === 'toValidate' ? 3 :
            ($type === 'done' ? 4 :
            ($type === 'cancelado'  ? 5 : null)))));

            // var_dump($estadoPrestamo);


        $data = $this->model->selectDetailReserva($pages, $estadoPrestamo);
        if (!$data['status']) {
            fail('error', $data);
        }
        success('Registros', $data);
    }



    public function getElementsReserva($codigo)
    {
        $codigoInt = (int) $codigo;
        $dataDetail = $this->model->selectElementsReserva($codigoInt);
        // $this->model->selectElementsReserva($codigoInt);
        success('Elementos relacionados al codigo', $dataDetail);
    }
}

$controller = new ReservaController();
//Valido si lo que se solicita es una petición ajax.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $case = $_GET['action'] ?? '';
        //valor de la página, por defecto, es la página #1.
        $pages = $_GET['pages'] ?? 1;

        $codigo = $_GET['codigo'] ?? 0;
        $codigo = (int) $codigo;
        switch ($case) {
            case 'elements':
                if (method_exists($controller, 'getElementosDevolutivos')) {
                    $controller->getElementosDevolutivos($pages);
                }
                break;
            case 'consumibles';
                //Elemento de tipo consumible;
                $type = 2;
                if (method_exists($controller, 'getElementosConsumibles')) {
                    $controller->getElementosConsumibles($pages, $type);
                }


                break;

            case 'reservas':

                $pages = (int) $_GET['pages'];
                $type = (String) $_GET['type'];
                if (method_exists($controller, 'getReservas')) {
                    $controller->getReservas($pages, $type);
                }
                break;

            case 'reservaDetailElements';

                if (method_exists($controller, 'getElementsReserva')) {
                    $controller->getElementsReserva($codigo);
                }

                break;

            case 'users':
                if (method_exists($controller, 'getUsers')) {
                    $controller->getUsers($pages);
                }
                break;

            default:
                //TODO: Retornar un valor no valido.
                # code...
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = file_get_contents("php://input");

        //TODO: validar si data llego bien, en caso de que no, devolver un error 500.
        $data = json_decode($input, true);

        switch ($data['action']) {
            case 'finalizar':

                $elementos = $data['data']["elementos"];
                $codigoReserva = $data['data']["codigoReserva"];

                $controller->setEndReserva($elementos, $codigoReserva);
                break;

            case 'registrar':
                $elementosPres = $data['data'];
                $controller->setReserva($elementosPres);
                break;
            case 'validateLoan':
                unset($data['action']);
                $dataNuevo = $data;
                $controller->setSolicitud($dataNuevo);

                //la validación del data es practicamente el setReserva pero la hare en otra función por cuestión de tiempo.


                break;
            default:
                break;
        }
    }
    exit();
}
