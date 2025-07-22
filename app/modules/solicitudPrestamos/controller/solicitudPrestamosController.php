<?php

include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../usuarios/model/usuariosModel.php';
include_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../helpers/response.php';

class solicitudPrestamosController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function registrarPrestamosView() {
    
        $idUsuario = $_SESSION['usuario']['id'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $obj = new usuarios();
        $datosU = $obj->searchU($idUsuario);
        
        // dd($datosU);
        
        $nombre = $datosU['usu_nombres'];
        $apellido =  $datosU['usu_apellidos'];
        $telefono = $datosU['usu_telefono'];
        $direccion = $datosU['usu_direccion'];
        $email = $datosU['usu_email']; 
        
        $objetoArea = new ConfigModulesModel();
        $areas = $objetoArea->select("SELECT * FROM areas WHERE ar_status = 1");
        
        // $objetoElemento = new ElementoModelo($this->conn);
        $objetoElemento = new ElementoModelo();
        $elementos = $objetoElemento->searchElements(1);
        $elementos_consumibles = $objetoElemento->searchElements(2);
        
        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }

    public function consultarPrestamosView() {
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

    public function registrarPrestamo() {
    $conn = $this->conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario_id = $_SESSION['usuario']['id'];
        $rol_id = $_SESSION['usuario']['rol_id'];
        $elementos_seleccionados = $_POST['elementos_seleccionados'];
        $cantidades_consumibles = $_POST['cantidades_consumibles'];
        $devolutivosElements = $_POST['elementos_devolutivos_seleccionados'];
        unset($_POST['elementos_seleccionados'],
        $_POST['elementos_devolutivos_seleccionados']);


        $data = $_POST;
        // dd($data);

        $datos = new solicitudPrestamos($this->conn);
        $lastId = $datos->create($data, $rol_id);

        if (is_numeric($lastId)) {
            $prestamoElemento = new solicitudPrestamos($this->conn);
            $elementoModel = new ElementoModelo();

            // Registrar devolutivos seleccionados
            foreach ($elementos_seleccionados as $elemento_id) {
                $typeElement = $elementoModel->getElementByType($elemento_id);
                $prestamoElemento->registrarElem($lastId, $usuario_id, $elemento_id);

                if ($typeElement == 2) {        
                    // Disminuye una unidad
                    $elementoModel->disminuirExistenciaElemento($elemento_id, 1);
                }
            
                // Cambiar estado
                $elementoModel->actualizarEstadoElemento($elemento_id, 5); 
            }


            // Registrar consumibles seleccionados
            foreach ($cantidades_consumibles as $elm_cod => $cantidad) {
                if (is_numeric($elm_cod) && is_numeric($cantidad) && $cantidad > 0) {
                    $prestamoElemento->registrarElemConsumible($lastId, $usuario_id, $elm_cod, $cantidad);
                    
                    // // Disminuye existencia sin tocar estado
                    // $elementoModel->disminuirExistenciaElemento($elm_cod, $cantidad);
            
                 
                    // $elementoModel->actualizarEstadoElemento($elm_cod, 3);
                }
            }

            // Registramos salidas
            $prestamoElemento->registrarSalida($cantidades_consumibles, $data['pres_fch_reserva'], $usuario_id, $lastId, $elementos_seleccionados);
  
                echo "<script>alert('Solicitud realizada correctamente, en espera por respuesta'); 
                      window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                exit;
            } else {
                echo "Error al registrar el préstamo: " . $lastId;
            }
        }
    }

    
    

    public function verDetallePrestamo(int $presCod) {
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

    public function obtenerElementosPorPrestamo($presCod) {

        $query = " SELECT 
            e.elm_nombre,
            e.elm_placa,
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
    private function obtenerEstadoNombre($id) {
        $stmt = $this->conn->prepare("SELECT es_pr_nombre FROM estados_prestamos WHERE es_pr_cod = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        return $res ? $res['es_pr_nombre'] : 'Desconocido';
    }

    private function obtenerTipoPrestamoNombre($id) {
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
    
    public function cancelarPrestamo() {
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
        
        //Realizar accion de entrada_Salidas al momento de cancelar Prestamo
 
    }



}


$conexion = new Conection();
$getConect = $conexion->getConnect();
$solicitudObj = new solicitudPrestamosController($getConect);

if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
    $pres_cod = (int) $_GET['pres_cod'];
    $solicitudObj->verDetallePrestamo($pres_cod);
}

// llamaa para cancelar el préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cancelar') {
    $solicitudObj->cancelarPrestamo(); // ← esto ya imprime un JSON válido
    exit;
}





?>
