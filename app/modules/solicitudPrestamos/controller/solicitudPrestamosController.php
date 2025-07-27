<?php

use SebastianBergmann\Environment\Console;

include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../usuarios/model/usuariosModel.php';
include_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../helpers/response.php';

class solicitudPrestamosController
{

    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function registrarPrestamosView()
    {

        $idUsuario = $_SESSION['usuario']['id'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $obj = new usuarios();
        $datosU = $obj->searchU($idUsuario);

        $nombre = $datosU['data']['usu_nombres'];
        $apellido =  $datosU['data']['usu_apellidos'];
        $telefono = $datosU['data']['usu_telefono'];
        $direccion = $datosU['data']['usu_direccion'];
        $email = $datosU['data']['usu_email'];

        $objetoArea = new ConfigModulesModel();
        $areas = $objetoArea->select("SELECT * FROM areas WHERE ar_status = 1");

        // $objetoElemento = new ElementoModelo($this->conn);
        $objetoElemento = new ElementoModelo();
        $elementos = $objetoElemento->searchElements(1);
        $elementos_consumibles = $objetoElemento->searchElements(2);

        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }

    public function consultarPrestamosView()
    {
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $id = $_SESSION['usuario']['id'];
        $prestamoModel = new solicitudPrestamos($this->conn);
        $prestamos = $prestamoModel->search($id);

        $objetoEstados = new ConfigModulesModel();
        $estados = $objetoEstados->select("SELECT * FROM estados_prestamos");

        return include_once __DIR__ . '/../views/consultarPrestamosView.php';
    }

    // public function registrarPrestamo(array $data = [])
    // {
    //     // header('Content-Type: application/json; charset=utf-8');
        
    //     try {
    //         if (!$data) {
    //         http_response_code(405);
    //         echo json_encode([
    //             "status" => "error",
    //             "message" => "Método no permitido. Usar POST."
    //         ]);
    //         exit;
    //         }
    //         if (session_status() == PHP_SESSION_NONE) {
    //             session_start();
    //         }
    
    //         // dd($_POST);
    //         // Validar que la sesión exista
    //         if (!isset($_SESSION['usuario'])) {
    //             http_response_code(401);
    //             echo json_encode([
    //                 "status" => "error",
    //                 "message" => "Sesión no válida. Debe iniciar sesión."
    //             ]);
    //             exit;
    //         }
            
    //         $usuario_id = $_SESSION['usuario']['id'];
    //         $rol_id = $_SESSION['usuario']['rol_id'];
            
            
    //         $elementosConsumibles = $data['elementos_consumibles'];
    //         $elementosDevolutivos = $data['elementos_devolutivos'];
            
            
    //         $objSolicitud = new solicitudPrestamos($this->conn);
    //         $lastId = $objSolicitud->create($data, $rol_id);
            
            // var_dump($lastId);
            // if (!is_numeric($lastId)) {
            //     http_response_code(500);
            //     echo json_encode([
            //         "status" => "error",
            //         "message" => "No se pudo registrar el préstamo.",
            //     ]);
            //     exit;
            // }
            
            // $elementoModel = new ElementoModelo();
            
            // var_dump($elementos_seleccionados);
            
            // foreach ($elementos_seleccionados as $elemento_id) {
            //     $typeElement = $elementoModel->getElementByType($elemento_id);
            //     $objSolicitud->registrarElem($lastId, $usuario_id, $elemento_id);
    
            //     if ($typeElement == 2) {
            //         $elementoModel->disminuirExistenciaElemento($elemento_id, 1);
            //     }
    
            //     $elementoModel->actualizarEstadoElemento($elemento_id, 5);
            // }
    
            // foreach ($cantidades_consumibles as $elm_cod => $cantidad) {
            //     if (is_numeric($elm_cod) && is_numeric($cantidad) && $cantidad > 0) {
            //         $objSolicitud->registrarElemConsumible($lastId, $usuario_id, $elm_cod, $cantidad);
            //     }
            // }
    
            // $objSolicitud->registrarSalida($cantidades_consumibles, $data['pres_fch_reserva'], $usuario_id, $lastId, $elementos_seleccionados);
    
            // http_response_code(200);
            // echo json_encode([
            //     "status" => "success",
            //     "message" => "Préstamo registrado correctamente.",
            //     "prestamo_id" => $lastId
            // ]);
            // exit;
    //     } catch (\Throwable $th) {
    //         http_response_code(505);
    //         json_encode([
    //             "status" => false,
    //             "message" => "$th"
    //         ]);
            
    //     }
        
        
    // }


    public function registrarPrestamo(array $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
    
        try {
            if (!$data) {
                http_response_code(405);
                echo json_encode([
                    "status" => "error",
                    "message" => "Método no permitido. Usar POST."
                ]);
                exit;
            }
    
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            // Validar que la sesión exista
            if (!isset($_SESSION['usuario'])) {
                http_response_code(401);
                echo json_encode([
                    "status" => "error",
                    "message" => "Sesión no válida. Debe iniciar sesión."
                ]);
                exit;
            }
    
            $usuario_id = $_SESSION['usuario']['id'];
            $rol_id = $_SESSION['usuario']['rol_id'];
    
            $elementosConsumibles = $data['elementos_consumibles'] ?? [];
            $elementosDevolutivos = $data['elementos_devolutivos'] ?? [];
            
            $objSolicitud = new solicitudPrestamos($this->conn);
            $lastId = $objSolicitud->create($data, $rol_id);
            
            if (!is_numeric($lastId)) {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo registrar el préstamo."
                ]);
                exit;
            }
            
            // Procesar elementos devolutivos
            $elementoModel = new ElementoModelo();
            
            foreach ($elementosDevolutivos as $item) {
                if (isset($item['codigo'])) {
                    $elemento_id = (int) $item['codigo'];
                    $typeElement = $elementoModel->getElementByType($elemento_id);
            
                    // Registrar elemento en la solicitud
                    $objSolicitud->registrarElem($lastId, $usuario_id, $elemento_id);
            
                    // Disminuir existencia si el tipo de elemento es 2 (consumible)
                    if ($typeElement = 1) {
                        $resultado = $elementoModel->disminuirExistenciaElemento($elemento_id, 1);
                       
                    }
            
                    // Actualizar estado del elemento
                    $elementoModel->actualizarEstadoElemento($elemento_id, 5);
                }
            }

        
            // Convertir array de objetos a array asociativo
            $cantidades_consumibles = [];
            foreach ($elementosConsumibles as $item) {
                if (isset($item['codigo'], $item['cantidad'])) {
                    $codigo = (int)$item['codigo'];
                    $cantidad = (int)$item['cantidad'];
                    if ($codigo > 0 && $cantidad > 0) {
                        $cantidades_consumibles[$codigo] = $cantidad;
                    }
                }
            }
            
            // Registrar consumibles
            foreach ($cantidades_consumibles as $elm_cod => $cantidad) {
                $objSolicitud->registrarElemConsumible($lastId, $usuario_id, $elm_cod, $cantidad);
            
                
                $elementoModel->disminuirExistenciaElemento($elm_cod, $cantidad);
               
            }

    
            $objSolicitud->registrarSalida($cantidades_consumibles, $data['pres_fch_reserva'], $usuario_id, $lastId, $elementosDevolutivos);
    
            // Respuesta éxito
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Préstamo registrado correctamente.",
                "prestamo_id" => $lastId
            ]);
            exit;
    
        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error interno: " . $th->getMessage()
            ]);
            exit;
        }
    }



    public function verDetallePrestamo(int $presCod)
    {
        if (!$presCod || !is_numeric($presCod)) {
            fail('Id no valido');
            return;
        }

        $modelo = new solicitudPrestamos($this->conn);
        $detalle = $modelo->searchU($presCod);
        if (!$detalle) {
            fail('No se encontró información del préstamo');
        }

        $detalle['pres_estado_nombre'] = $this->obtenerEstadoNombre($detalle['pres_estado']);
        //el tipo del prestamo
        $detalle['tp_pres_nombre'] = $this->obtenerTipoPrestamoNombre($detalle['tp_pres']);
        //nombre del rol solicitante
        $detalle['pres_rol_nombre'] = $this->obtenerRolNombre($detalle['pres_rol']);

        // Consulto los elementos del prestamo
        $elementos = $this->obtenerElementosPorPrestamo($presCod);
        $detalle['elementos'] = $elementos;

        success('Detalle del prestamo', $detalle);
    }

    public function obtenerElementosPorPrestamo($presCod)
    {

        $query = " SELECT 
        e.elm_cod,
            e.elm_nombre,
            e.elm_placa,
            e.elm_cod_tp_elemento,
            pe.pres_el_cantidad AS cantidad
        FROM 
            elementos e 
            INNER JOIN prestamos_elementos pe ON pe.pres_el_elem_cod = e.elm_cod
            INNER JOIN prestamos pr ON pr.pres_cod = pe.pres_cod
        WHERE 
            pe.pres_cod = ?
        ORDER BY 
        e.elm_nombre DESC";


        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $presCod);
        $stmt->execute();
        $result = $stmt->get_result();

        $elementos = [];
        while ($row = $result->fetch_assoc()) {
            $elementos[] = $row;
        }

        return $elementos;
    }


    //Pendiente nnviar al solicitudPrestamoModel - consultas para detalle prestamos Modal
    private function obtenerEstadoNombre($id)
    {
        $stmt = $this->conn->prepare("SELECT es_pr_nombre FROM estados_prestamos WHERE es_pr_cod = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        return $res ? $res['es_pr_nombre'] : 'Desconocido';
    }

    private function obtenerTipoPrestamoNombre($id)
    {
        $stmt = $this->conn->prepare("SELECT tp_nombre FROM tipo_prestamo WHERE tp_pre = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        return $res ? $res['tp_nombre'] : 'Desconocido';
    }

    private function obtenerRolNombre($id) {
        $stmt = $this->conn->prepare("SELECT rl_nombre FROM roles WHERE rl_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        return $res ? $res['rl_nombre'] : 'Desconocido';
    }

    public function cancelarPrestamo()
    {
        header('Content-Type: application/json');

        $presCod = isset($_POST['pres_cod']) ? (int) $_POST['pres_cod'] : null;

        if (!$presCod) {
            die(json_encode([
                'success' => false,
                'message' => 'Código inválido del préstamo'
            ]));
        }

        $modelo = new solicitudPrestamos($this->conn);
        $resultado = $modelo->cancelarPrestamo($presCod);
        echo json_encode($resultado);
        exit;
    }
}


$conexion = new Conection();
$getConect = $conexion->getConnect();
$solicitudObj = new solicitudPrestamosController($getConect);
// $solicitudObj->cancelarPrestamo(530);

// NUEVO: Manejo de solicitudes tipo JSON (por fetch con application/json)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && stripos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
    // $input = file_get_contents("php://input");
    // $data = json_decode($input, true);
    
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    if (is_array($data) && isset($data['action'])) {
        switch ($data['action']) {
            case 'registrarPrestamo':
                // Convertir los datos como si vinieran por $_POST para mantener compatibilidad
                
                unset($data['action']);
                $newData = $data;
                $solicitudObj->registrarPrestamo($newData);
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Acción no válida"
                ]);
                exit;
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "No se recibió informacion válida en los datos"
        ]);
        exit;
    }
}

if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
    $pres_cod = (int) $_GET['pres_cod'];
    $solicitudObj->verDetallePrestamo($pres_cod);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cancelar') {
    $solicitudObj->cancelarPrestamo();
    exit;
}
