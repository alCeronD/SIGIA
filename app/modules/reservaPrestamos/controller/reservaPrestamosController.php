<?php

require_once __DIR__ . '/../../../helpers/session.php';
require_once __DIR__ . '/../../../helpers/validatePermisos.php';
require_once __DIR__ . '/../../../helpers/validateFecha.php';
require_once __DIR__ . '/../../../helpers/getUrl.php';
require_once __DIR__ . '/../model/reservaModel.php';
require_once __DIR__ . '/../../usuarios/model/usuariosModel.php';


// Recibir la respuesta de la solicitud.
$method = $_SERVER['REQUEST_METHOD'];

class reservaPrestamosController
{
    private ReservaModel $model;

    private ElementoModelo $modelElemento;
    public function __construct()
    {
        $reservaModel = new ReservaModel();
        $this->model = $reservaModel;
        $elementoModel = new ElementoModelo();
        $this->modelElemento = $elementoModel;
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
        // validatePermisos('reservaPrestamos', 'getElementosDevolutivos');
        $data = $this->model->selectElements($pages);
        success('Registros', $data);
    }
    
    public function getElementosConsumibles(int $pages = 1, int $type = 2)
    {
        // validatePermisos('reservaPrestamos', 'getElementosConsumibles');
        $data = $this->model->selectElements($pages, $type);
        success('elementos consumibles', $data);
    }
    
    public function getUsers($page)
    {
        // validatePermisos('reservaPrestamos', 'getUsers');
        $data = $this->model->selectUsers($page);

        if ($data != null) {
            success('Usuarios activos', $data);
        }
    }
    //Función para traer las reservas
    public function getReservas(int $pages = 0, String $type = '')
    {

        // validatePermisos('reservaPrestamos', 'getReservas');

        if (!$pages) {
            fail('pagina no definida');
        }

        // Puedo guardar los estados en un arreglo y validarlo con la clave del arreglo que me recibe.
        $estadoPrestamo =
            $type === 'all'       ? 0 : ($type === 'validate'   ? 1 : ($type === 'Rechazado'  ? 2 : ($type === 'toValidate' ? 3 : ($type === 'done' ? 4 : ($type === 'cancel'  ? 5 : null)))));

        $data = $this->model->selectDetailReserva($pages, $estadoPrestamo);
        if (!$data['status'] && empty($data['data'])) {
            success('No hay registros.', $data);
        }
        success('Registros', $data);
    }
    public function getElementsReserva($codigo)
    {
        // validatePermisos('reservaPrestamos','getElementsReserva');
        $codigoInt = (int) $codigo;
        $dataDetail = $this->model->selectElementsReserva($codigoInt);
        success('Elementos relacionados al codigo', $dataDetail);
    }

    /**
     * Definimos la estructura para guardar el prestamo o la reserva en la base de datos.
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
        validatePermisos('reservaPrestamos', 'setReserva');
        $tp_pres = isset($data['tpPrestamo']) ? (int) $data['tpPrestamo'] : null;
        $pres_estado = null;
        $pres_rol = $_SESSION['usuario']['rol_id'];

        $codConsumibles = $data["codigosElementos"]['consumibles'];
        $codDevolu = $data["codigosElementos"]['devolutivos'];

        $ascDevolutivos = array_column($codDevolu, 'codigo');
        $ascConsu = array_column($codConsumibles, 'codigo');
        //Cordenar los elementos del arreglo basado en el código
        array_multisort($codDevolu, SORT_ASC, $ascDevolutivos);
        array_multisort($codConsumibles, SORT_ASC, $ascConsu);

        if ($tp_pres == 2) {
            //Cambiar nombre de la llave.
            $data['pres_fch_reserva'] = $data['fechaReserva'];
            unset($data['fechaReserva']);
        }

        $pres_estado = $tp_pres == 2 ? 3 : 1;

        unset($data["codigosElementos"]);
        $data['pres_fch_entrega'] = $data['fechaDevolucion'];
        unset($data['fechaDevolucion']);
        $data['pres_observacion'] = $data['observaciones'];
        unset($data['observaciones']);
        $data['pres_destino'] = $data['areaDestino'];
        unset($data['areaDestino']);
        $data['pres_estado'] = $pres_estado;
        $data['pres_rol'] = $pres_rol;
        $data['tp_pres'] = $tp_pres;
        unset($data['tpPrestamo']);


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

        validatePermisos('reservaPrestamos', 'setSolicitud');
        $cedula = $data['dataUsuario']['nroIdentidad'];

        $result = $this->model->validateSolicitud($data, $cedula);
        if (!$result) {
            fail('error al validar el prestamo');
        }
        success('prestamo validado');
    }

    //Finalizar la reserva, es decir, cuando el usuario devuelve los elementos.
    public function setEndReserva(array $elementos = [], int $codigo = 0, array $data = [])
    {
        validatePermisos('reservaPrestamos', 'setEndReserva');
        $data = $this->model->endReserva($elementos, $codigo, $data);
        if ($data['status']) {
            success(value: 'Prestamo finalizado.');
        }
    }

    // public function validateElemento(int $elemento = 0,String $fechaReserva = "" ,$isOnly = false, array $elementos = [], int $tpPrestamo = 0){
    //     if ($isOnly) {
    //         $result = $this->modelElemento->validateDisponiblidad($elemento, $isOnly);
    //         $data = $result['data'];
    //         // Validamos si hay resultados para ejecutar la operación y devolver la respuesta.
    //         if (count($result['data']) > 0) {
    //             // 0 Porque está en la primera posición del resultado data.
    //             $fechaResult = $data[0]['fechaReserva'];
    //             if (validateFecha($fechaReserva, $fechaResult, true)) {
    //                 fail("El elemento $elemento está reservado para la fecha $fechaResult", $result);
    //             }
    //         } else {
    //             noResponse($result);
    //         }
    //     }else{
    //         $resultElementos = $this->modelElemento->validateDisponiblidad(
    //             isOnly:$isOnly, 
    //             elementos:$elementos
    //         );
    //         $data = $resultElementos['data'];
    //         $elementosYaSeleccionados = [];
    //         foreach ($data as $key => $value) {
    //             $fechaReservaElementos = $value['fechaReserva'];
    //             $fechaDevolucionElementos = $value['fechaDevolucion'];
    //             if (validateFecha(
    //                 date1: $fechaReservaElementos,
    //                 date2: $fechaReserva,
    //                 date3: $fechaDevolucionElementos,
    //                 tpPrestamo:$tpPrestamo
    //             )) {
    //                 $elementosYaSeleccionados[]= $value;
    //             }
    //         }

    //         // No le estoy dando uso porque no pude usar la respuesta de fail o success.
    //         $resultado = [
    //             'data'=>$elementosYaSeleccionados,
    //             'status'=> false,
    //             'message'=>'Elementos ya seleccionados'
    //         ];

    //         if (count($elementosYaSeleccionados)=== 0) {
    //             noResponse($resultElementos);
    //         }else{
    //             // fail('Hay elementos seleccionados que están reservados para la fecha seleccionada', $resultElementos);
    //             // Puedo implementar la función fail pero por ahora se deja a parte por temas de tiempo.
                
    //             $response = [
    //                 'status' => true,
    //                 'message' => 'Elementos ya seleccionados',
    //                 'data' => $elementosYaSeleccionados
    //             ];
    //             http_response_code(200);
    //             echo json_encode($response, JSON_PRETTY_PRINT);
    //             exit();
    //         }
    //     }

    // }


    public function validateElemento(int $elemento = 0,String $fechaReserva = "" ,$isOnly = false, array $elementos = [], int $tpPrestamo = 0){


        if ($isOnly) {
            $result = $this->modelElemento->validateDisponiblidad($elemento, $isOnly);
            $data = $result['data'];
            if (count($data) > 1 && count($data) < 2) {
                // 0 Porque está en la primera posición del resultado data.
                $fechaResDB = $data[0]['fechaReserva'];
                $fechaDevDb = $data[0]['fechaDevolucion'];
                // if (validateFecha($fechaReserva, $fechaResult, true)) {
                if (validateFecha(date1:$fechaReserva, date2:$fechaResDB,date3:$fechaDevDb,isFormat:true, tpPrestamo:$tpPrestamo)) {

                    fail("El elemento $elemento está reservado para la fecha $fechaResDB", $result);
                }else{
                    noResponse($result);
                }
            } else if(count($data) > 1) {
                foreach ($data as $key => $value) {
                    $fechaPorValidar = $value['fechaReserva'];
                    $fechaDevolucion = $value['fechaDevolucion'];
                    if (!validateFecha(date1: $fechaReserva, date2: $fechaPorValidar, date3: $fechaDevolucion, isFormat: true, tpPrestamo: $tpPrestamo)) {
                        $responseValidate = [
                            'status'=>false,
                            'message'=>'El elemento $elemento está reservado para la fecha $fechaPorValidar',
                            'data'=> []
                        ];
                        http_response_code(200);
                        echo json_encode($responseValidate, JSON_PRETTY_PRINT);
                        exit();
                    }
                }

                noResponse($result);
            }
            noResponse($result);
        
        
        }else{
            $resultElementos = $this->modelElemento->validateDisponiblidad(
                isOnly:$isOnly, 
                elementos:$elementos
            );
            $data = $resultElementos['data'];
            $elementosYaSeleccionados = [];
            foreach ($data as $key => $value) {
                $fechaReservaElementos = $value['fechaReserva'];
                $fechaDevolucionElementos = $value['fechaDevolucion'];
                if (validateFecha(
                    date1: $fechaReservaElementos,
                    date2: $fechaReserva,
                    date3: $fechaDevolucionElementos,
                    tpPrestamo:$tpPrestamo
                )) {
                    $elementosYaSeleccionados[]= $value;
                }
            }

            // No le estoy dando uso porque no pude usar la respuesta de fail o success.
            $resultado = [
                'data'=>$elementosYaSeleccionados,
                'status'=> false,
                'message'=>'Elementos ya seleccionados'
            ];

            if (count($elementosYaSeleccionados)=== 0) {
                noResponse($resultElementos);
            }else{
                // fail('Hay elementos seleccionados que están reservados para la fecha seleccionada', $resultElementos);
                // Puedo implementar la función fail pero por ahora se deja a parte por temas de tiempo.
                
                $response = [
                    'status' => true,
                    'message' => 'Elementos ya seleccionados',
                    'data' => $elementosYaSeleccionados
                ];
                http_response_code(200);
                echo json_encode($response, JSON_PRETTY_PRINT);
                exit();
            }
        }

    }
}

$controller = new reservaPrestamosController();
//Valido si lo que se solicita es una petición ajax.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $case = $_GET['action'] ?? '';
        //valor de la página, por defecto, es la página #1.
        $pages = $_GET['pages'] ?? 1;

        $codigo = $_GET['codigo'] ?? 0;
        $codigo = (int) $codigo;

        switch ($case) {
            // TODO: Revisar, ya que estos 2 cases traen la info pero uno trae la pagina y su tipo, el otro no.
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

            case 'elementsDevolutivos':
                if (method_exists($controller, 'getElementosDevolutivos')) {
                    $controller->getElementosDevolutivos($pages);
                }
                break;
            case 'elementsConsumibles';
                //Elemento de tipo consumible;
                $type = 2;
                if (method_exists($controller, 'getElementosConsumibles')) {
                    $controller->getElementosConsumibles($pages, $type);
                }


                break;

            case 'reservas':

                $pages = (int) $_GET['pages'];
                $type = (string) $_GET['type'];
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

                // Valido los elementos por y los mando por get porque se hace 1 por uno.
            case 'validateElement':
                $isOnly = $_GET['isOnly'] === "true" ? true : false;
                if($isOnly) {
                    $elementos = (int) $_GET['elementos'] ?? null;
                }else{
                    $elementos = $_GET['elementos'] ?? [];
                }

                $fecha = empty($_GET['fechaReserva']) ? "" : $_GET['fechaReserva'];
                $tpPrestamo = empty($_GET['tpPrestamo']) ? "" : (int) $_GET['tpPrestamo'];

                if (method_exists($controller, 'validateElemento')) {
                    // $controller->validateElemento($elementos, $fecha, $isOnly);
                    $controller->validateElemento(
                        elemento:$elementos,
                        fechaReserva:$fecha,
                        isOnly:$isOnly,
                        tpPrestamo:$tpPrestamo
                    );
                }
                break;

            default:
                //TODO: Retornar un valor no valido.

                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = file_get_contents("php://input");
        //TODO: validar si data llego bien, en caso de que no, devolver un error 500.
        $data = json_decode($input, true);
        switch ($data['action']) {
            case 'finalizar':
                unset($data['action']);
                $elementos = $data["elementos"];
                $codigoReserva = $data["codigoReserva"];
        


                $controller->setEndReserva($elementos, $codigoReserva, $data);
                break;

            case 'registrar':

                $elementosPres = $data;
                $controller->setReserva($elementosPres);
                break;
            case 'validateLoan':
                unset($data['action']);
                $dataNuevo = $data;
                $controller->setSolicitud($dataNuevo);
                break;

                // Valido el listado de los elementos después de que el usuario haya seleccioando todos sus datos, mediante un arreglo.
                case 'validateElements':

                    $isOnly = $data['isOnly'];
                    $fechaReserva = $data['fechaReserva'];
                    $elementos = $data['elementos'];
                    $tpPrestamo = (int) $data['tpPrestamo'];

                    $controller->validateElemento(isOnly: $isOnly, fechaReserva: $fechaReserva, elementos: $elementos, tpPrestamo: $tpPrestamo);

                break;

            default:
                break;
        }
    }
    exit();
}
