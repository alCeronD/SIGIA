<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include_once __DIR__ . '/../../elementos/model/elementosModel.php';
include_once __DIR__ . '/../model/reportesModel.php';
include_once __DIR__ . '/../../../helpers/response.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../../helpers/session.php';

class ReportesController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function genReporteView() {
    
        $_SESSION['css'] = 'reportes/reportes.css';
    
        $objElm = new ElementoModelo();
        $objEstElm = new ReportesModel();
    
        $estados = $objEstElm->getEstadosReport();
        $tipos = $objEstElm->tipoElemento();
    
        $estadoSeleccionado = $_GET['estadoElemento'] ?? '';
        $tipoSeleccionado = $_GET['tipoElemento'] ?? '';
    
        if (!empty($estadoSeleccionado) || !empty($tipoSeleccionado)) {
            $elementos = $objEstElm->obtenerElementosFiltrados($tipoSeleccionado, $estadoSeleccionado);
        } else {
            $elementos = $objElm->obtenerElemento();
        }
    
        include_once __DIR__ . '/../views/reporteElementosView.php';
    }

    public function filtrarElementosAjax() {
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
    
        header('Content-Type: application/json');
    
        $estado = $_POST['estadoElemento'] ?? '';
        $tipo = $_POST['tipoElemento'] ?? '';
    
        $modelo = new ReportesModel();
        $datos = (!empty($estado) || !empty($tipo))
            ? $modelo->obtenerElementosFiltrados($tipo, $estado)
            : (new ElementoModelo())->obtenerElemento();
    
        echo json_encode($datos);
        exit;
    }



    public function generarReporteExcel() {
        require_once __DIR__ . '/../../../../vendor/autoload.php';
    
        
    
        $estado = $_GET['estadoElemento'] ?? '';
        $tipo = $_GET['tipoElemento'] ?? '';
        
        $modelo = new ReportesModel();
        $elementos = (!empty($estado) || !empty($tipo))
            ? $modelo->obtenerElementosFiltrados($tipo, $estado)
            : (new ElementoModelo())->obtenerElemento();

    
        // dd($elementos);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Encabezados
        $sheet->setCellValue('A1', 'Código');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Placa');
        $sheet->setCellValue('D1', 'Existencia');
        $sheet->setCellValue('E1', 'Estado');
    
        // Contenido
        $row = 2;
        foreach ($elementos as $elemento) {
            $sheet->setCellValue("A{$row}", $elemento['codigoElemento']);
            $sheet->setCellValue("B{$row}", $elemento['nombreElemento']);
            $sheet->setCellValue("C{$row}", $elemento['placa'] ?? '—');
            $sheet->setCellValue("D{$row}", $elemento['cantidad'] ?? 0);
            $sheet->setCellValue("E{$row}", $elemento['estadoElemento']);
            $row++;
        }
    
        // Descargar
        if (ob_get_length()) ob_end_clean(); // 🛠️ Limpia salida previa
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteElementos.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
