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

    public function genReporteView()
    {
        $_SESSION['css'] = 'reportes/reportes.css';
    
        $objElm    = new ElementoModelo();
        $reportMdl = new ReportesModel();
    
        $estados = $reportMdl->getEstadosReport();
        $tipos   = $reportMdl->tipoElemento();
    
        /* ----------------------------------------------------------
         * 4. Parámetros recibidos por GET
         *    -  Para elementos:       estadoElemento / tipoElemento
         *    -  Para trazabilidad:    fi / ff / tipoElemento
         * ---------------------------------------------------------- */
        $estadoSeleccionado = $_GET['estadoElemento'] ?? '';
        $tipoSeleccionado   = $_GET['tipoElemento']   ?? '';
    
        $fechaInicio = $_GET['fi'] ?? '';   
        $fechaFin    = $_GET['ff'] ?? '';   
    
           
        /* Si trae fechas “Trazabilidad” */
        if ($fechaInicio !== '' && $fechaFin !== '') {
            $elementos     = []; 
            $trazabilidad  = $reportMdl->consultEntSal($tipoSeleccionado, $fechaInicio, $fechaFin);
        }
        /* Si se llama al filtro elementos */
        elseif ($estadoSeleccionado !== '' || $tipoSeleccionado !== '') {
            $trazabilidad = []; // no cargamos movimientos
            $elementos    = $reportMdl->obtenerElementosFiltrados($tipoSeleccionado, $estadoSeleccionado);
        }
        /* sin filtrocarga todo */
        else {
            $trazabilidad = [];
            $elementos    = $objElm->obtenerElemento();
        }
    
      
        include_once __DIR__.'/../views/reporteElementosView.php';
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
    
    public function generarReporteTrazabilidad()
        {
            // 1.  Filtros recibidos por GET
            $tipo        = $_GET['tipoElemento'] ?? '';
            $fechaInicio = $_GET['fi'] ?? '';
            $fechaFin    = $_GET['ff'] ?? '';
        
            if ($fechaInicio === '' || $fechaFin === '') {
                exit('Rango de fechas no válido.');
            }
        
            // 2.  Consultar datos
            $modelo   = new ReportesModel();
            $registros = $modelo->consultEntSal($tipo, $fechaInicio, $fechaFin);
        
            // 3.  Crear Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
        
            // Encabezados
            $sheet->fromArray(
                ['Código', 'Nombre', 'Placa', 'Cantidad', 'Tipo movimiento', 'Fecha'],
                null,
                'A1'
            );
        
            // Contenido
            $row = 2;
            foreach ($registros as $r) {
                $sheet->setCellValue("A{$row}", $r['codigoElemento']);
                $sheet->setCellValue("B{$row}", $r['nombreElemento']);
                $sheet->setCellValue("C{$row}", $r['placa'] ?? '—');
                $sheet->setCellValue("D{$row}", $r['cantidad']);
                $sheet->setCellValue("E{$row}", $r['tipoMovimiento']);  // 'Entrada' / 'Salida'
                $sheet->setCellValue("F{$row}", $r['fechaMovimiento']);
                $row++;
            }
        
            // 4.  Descargar
            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="ReporteTrazabilidad.xlsx"');
            header('Cache-Control: max-age=0');
        
            (new Xlsx($spreadsheet))->save('php://output');
            exit;
        }

    
    
    public function filtrarTrazabilidadAjax() {
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
    
        header('Content-Type: application/json');
    
        $tipo = $_POST['tipoElemento'] ?? '';
        $fechaInicio = $_POST['fechaInicio'] ?? '';
        $fechaFin = $_POST['fechaFin'] ?? '';
        if (empty($fechaInicio) || empty($fechaFin)) {
            echo json_encode([]);
            return;
        }
    
        $modelo = new ReportesModel();
        $datos  = $modelo->consultEntSal($tipo, $fechaInicio, $fechaFin);
        
        echo json_encode($datos);
        exit;

    }


}
