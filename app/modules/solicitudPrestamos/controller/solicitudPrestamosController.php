<?php

include_once __DIR__ . '/../model/solicitudPrestamosModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../configModules/model/configModulesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../../../helpers/session.php';
include_once __DIR__ . '/../../../helpers/response.php';

class solicitudPrestamosController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function registrarPrestamosView() {
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];

        $objetoArea = new ConfigModulesModel();
        $areas = $objetoArea->select("SELECT * FROM areas");

        $objetoElemento = new ElementoModelo($this->conn);
        $elementos = $objetoElemento->searchElements();

        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }

    public function consultarPrestamosView() {
        $nombre = $_SESSION['usuario']['nombre'];
        $apellido = $_SESSION['usuario']['apellido'];
        $rol_nombre = $_SESSION['usuario']['rol_nombre'];
        $id = $_SESSION['usuario']['id'];

        $prestamoModel = new solicitudPrestamos($this->conn);

        $prestamos = $prestamoModel->search($id);
        return include_once __DIR__ . '/../views/consultarPrestamosView.php';
    }

    public function registrarPrestamo() {
        $conn = $this->conn;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = $_SESSION['usuario']['id'];
            $rol_id = $_SESSION['usuario']['rol_id'];
            $elementos_seleccionados = $_POST['elementos_seleccionados'];
            unset($_POST['elementos_seleccionados']);
            $data = $_POST;

            $datos = new solicitudPrestamos($this->conn);
            $lastId = $datos->create($data, $rol_id);
            if (is_numeric($lastId)) {
                // include_once __DIR__ . '/../../configModules/prestamosElementos/model/prestamosElementosModel.php';
                $prestamoElemento = new solicitudPrestamos($this->conn);
                $elementoModel = new ElementoModelo($this->conn);
                foreach ($elementos_seleccionados as $elemento_id) {
                    $prestamoElemento->registrarElem($lastId, $usuario_id, $elemento_id);
                    $elementoModel->actualizarEstadoElemento($elemento_id, 3);
                }
                if ($prestamoElemento == true) {
                    echo "<script>alert('Solicitud realizada correctamente, en espera por respuesta'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                } else {
                    echo "<script>alert('Prestamo no se registro'); window.location.href = '" . getUrl('solicitudPrestamos','solicitudPrestamos','registrarPrestamosView', false, 'dashboard') . "';</script>";
                }
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
                tp.tp_el_nombre AS tipoElemento
            FROM 
                elementos e 
                INNER JOIN prestamos_elementos pe ON pe.pres_el_elem_cod = e.elm_cod
                INNER JOIN prestamos pr ON pr.pres_cod = pe.pres_cod
                INNER JOIN tipo_elemento tp ON tp.tp_el_cod = e.elm_cod_tp_elemento
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
        $stmt = $this->conn->prepare("SELECT es_pr_cod FROM estados_prestamos WHERE es_pr_cod = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        return $res ? $res['es_pr_cod'] : 'Desconocido';
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
    $modelo->cancelarPrestamo($presCod);
    success('Estado cancelado'); 
    // echo json_encode([
    //         'success' => true,
    //         'message' => 'Se cancelo el prestamo solicitado
    // ]);    
    // json_encode($resultado);
    // exit;
}



}


//pendiente solucion para enviar a fetchSolicitudPrestamo.
$conexion = new Conection();
$getConect = $conexion->getConnect();
$solicitudObj = new solicitudPrestamosController($getConect);

if (isset($_GET['pres_cod']) && isset($_GET['idCod'])) {
    $pres_cod = (int) $_GET['pres_cod'];
    $solicitudObj->verDetallePrestamo($pres_cod);
}

// llamaa para cancelar el préstamo
if (isset($_GET['accion']) && $_GET['accion'] === 'cancelar' && isset($_GET['pres_cod'])) {
    $pres_cod = (int) $_GET['pres_cod'];
    $solicitudObj->cancelarPrestamo();
}




?>
