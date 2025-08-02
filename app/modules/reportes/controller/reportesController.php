<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// Esto es para escribir la información en el excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// Este paquete lo uso para conocer la cantidad de celdas y así hacer el filtrado de manera dinámica.
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
include_once __DIR__ . '/../model/reportesModel.php';
include_once __DIR__ . '/../../elementos/model/elementosModel.php';

include_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../helpers/validatePermisos.php';

class ReportesController {

    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    /* ------------------------------------------------------------------
     * VISTA PRINCIPAL
     * ----------------------------------------------------------------*/
    public function genReporteView() {

        validatePermisos('reportes', 'genReporteView');

        $_SESSION['css'] = 'reportes/reportes.css';
        $objElm    = new ElementoModelo();
        $reportMdl = new ReportesModel();

        $estados = $reportMdl->getEstadosReport();
        $tipos   = $reportMdl->tipoElemento();

        // --- parámetros GET (elementos / trazabilidad) ---
        $estadoSeleccionado = $_GET['estadoElemento'] ?? '';
        $tipoSeleccionado   = $_GET['tipoElemento']   ?? '';
        $fechaInicio        = $_GET['fi']             ?? '';
        $fechaFin           = $_GET['ff']             ?? '';

        // --- decide qué dataset precargar (solo para la pre‑visualización) ---
        if ($fechaInicio && $fechaFin) {                       
            $elementos    = [];
            $trazabilidad = $reportMdl->consultEntSal($tipoSeleccionado, $fechaInicio, $fechaFin);
        } elseif ($estadoSeleccionado || $tipoSeleccionado) {  
        
            $trazabilidad = [];
            $elementos    = $reportMdl->obtenerElementosFiltrados($tipoSeleccionado, $estadoSeleccionado);
        } else {                                               
            $trazabilidad = [];
            $elementos    = $objElm->obtenerElemento();
        }

        include_once __DIR__.'/../views/reporteElementosView.php';
    }

    /* ------------------------------------------------------------------
     * AJAX ELEMENTOS
     * ----------------------------------------------------------------*/
    public function filtrarElementosAjax() {
        validatePermisos('reportes','filtrarElementosAjax');
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
        header('Content-Type: application/json');

        $estado = $_POST['estadoElemento'] ?? '';
        $tipo   = $_POST['tipoElemento']   ?? '';

        $mdl   = new ReportesModel();
        $datos = ($estado || $tipo)
               ? $mdl->obtenerElementosFiltrados($tipo, $estado)
               : (new ElementoModelo())->obtenerElemento();

        echo json_encode($datos);
        exit;
    }

    /* ------------------------------------------------------------------
     * AJAX TRAZABILIDAD (Entradas / Salidas)
     * ----------------------------------------------------------------*/
    public function filtrarTrazabilidadAjax() {
        validatePermisos('reportes', 'filtrarTrazabilidadAjax');
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
        header('Content-Type: application/json');

        $tipo        = $_POST['tipoElemento'] ?? '';
        $fechaInicio = $_POST['fechaInicio']  ?? '';
        $fechaFin    = $_POST['fechaFin']     ?? '';
        if (!$fechaInicio || !$fechaFin) {
            echo json_encode([]);
            return;
        }

        $mdl   = new ReportesModel();
        $datos = $mdl->consultEntSal($tipo, $fechaInicio, $fechaFin);
        echo json_encode($datos);
        exit;
    }

    /* ------------------------------------------------------------------
     * AJAX MOVIMIENTOS POR PLACA
     * ----------------------------------------------------------------*/
    public function filtrarPorPlacaAjax() {
        validatePermisos('reportes', 'filtrarPorPlacaAjax');
        if (!ajaxGeneral()) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no permitido']);
            exit;
        }
        header('Content-Type: application/json');

        $placa = trim($_POST['placa'] ?? '');
        if ($placa === '') { echo json_encode([]); return; }

        $mdl   = new ReportesModel();
        $datos = $mdl->consultarMovimientosPorPlaca($placa);
        echo json_encode($datos);
        exit;
    }

    // generar filtro automático
    public function setAutoFiltro($sh, array $filtros = []) {
        // validatePermisos('reportes', 'setAutoFiltro');
        $sh->fromArray($filtros, null, 'A1');

        // Traigo la longitud del arreglo.
        $colIndex = count($filtros);
        // traigo el numero de indices que hay por columna y lo tranformo en letras.
        $lastCol = Coordinate::stringFromColumnIndex($colIndex); 

        // Aplicar autofiltro en el rango correcto (solo en la fila 1)
        $sh->setAutoFilter("A1:{$lastCol}1");
    }

    /* ------------------------------------------------------------------
     * REPORTE EXCEL – ELEMENTOS
     * ----------------------------------------------------------------*/
    public function generarReporteExcel() {
        validatePermisos('reportes', 'generarReporteExcel');
        $estado = $_GET['estadoElemento'] ?? '';
        $tipo   = $_GET['tipoElemento']   ?? '';

        $mdl = new ReportesModel();
        $elementos = ($estado || $tipo)
                   ? $mdl->obtenerElementosFiltrados($tipo, $estado)
                   : (new ElementoModelo())->obtenerElemento();

        $ss   = new Spreadsheet();
        $sh   = $ss->getActiveSheet();
        $encabezadosElementos = ['Código', 'Nombre', 'Placa', 'Existencia', 'Estado'];
        $sh->fromArray($encabezadosElementos, null, 'A1');
        $this->setAutoFiltro($sh,$encabezadosElementos);
        $row = 2;
        foreach ($elementos as $e) {
            $sh->fromArray([
                $e['codigoElemento'],
                $e['nombreElemento'],
                $e['placa'] ?? '—',
                $e['cantidad'] ?? 0,
                $e['estadoElemento']
            ], null, "A{$row}");
            $row++;
        }

        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteElementos.xlsx"');
        header('Cache-Control: max-age=0');
        (new Xlsx($ss))->save('php://output');
        exit;
    }

    /* ------------------------------------------------------------------
     * REPORTE EXCEL – TRAZABILIDAD
     * ----------------------------------------------------------------*/
    public function generarReporteTrazabilidad() {
        validatePermisos('reportes','generarReporteTrazabilidad');
        $tipo        = $_GET['tipoElemento'] ?? '';
        $fechaInicio = $_GET['fi'] ?? '';
        $fechaFin    = $_GET['ff'] ?? '';
        if (!$fechaInicio || !$fechaFin) exit('Rango de fechas no válido.');

        $filtro = ['Código','Nombre','Placa','Cantidad','Tipo movimiento','Fecha'];

        $mdl   = new ReportesModel();
        $regs  = $mdl->consultEntSal($tipo, $fechaInicio, $fechaFin);

        $ss = new Spreadsheet();
        $sh = $ss->getActiveSheet();
        $this->setAutoFiltro($sh,$filtro);

        $row = 2;
        foreach ($regs as $r) {
            $sh->fromArray([
                $r['codigoElemento'],
                $r['nombreElemento'],
                $r['placa'] ?? '—',
                $r['cantidad'],
                $r['tipoMovimiento'],
                $r['fechaMovimiento']
            ], null, "A{$row}");
            $row++;
        }

        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteTrazabilidad.xlsx"');
        (new Xlsx($ss))->save('php://output');
        exit;
    }
    
     /* ------------------------------------------------------------------
     * REPORTE EXCEL – PLACa
     * ----------------------------------------------------------------*/
    public function generarReportePorPlaca() {
        validatePermisos('reportes', 'generarReportePorPlaca');
        $placa = $_GET['placaElemento'] ?? '';
    
        if (empty($placa)) {
            exit('Debe especificar una placa.');
        }
    
        $modelo = new ReportesModel();
        $registros = $modelo->consultarMovimientosPorPlaca($placa);
    
        if (empty($registros)) {
            exit('No se encontraron movimientos para la placa ingresada.');
        }
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $filtrosEncabezados = ['Código', 'Nombre', 'Placa', 'Cantidad', 'Tipo Movimiento', 'Fecha Movimiento'];
        // Encabezados
        $sheet->fromArray(
            $filtrosEncabezados,
            null,
            'A1'
        );

        $this->setAutoFiltro($sheet, $filtrosEncabezados);
    
        // Contenido
        $row = 2;
        foreach ($registros as $r) {
            $sheet->setCellValue("A{$row}", $r['codigoElemento']);
            $sheet->setCellValue("B{$row}", $r['nombreElemento']);
            $sheet->setCellValue("C{$row}", $r['placa'] ?? '—');
            $sheet->setCellValue("D{$row}", $r['cantidad']);
            $sheet->setCellValue("E{$row}", $r['tipoMovimiento']);
            $sheet->setCellValue("F{$row}", $r['fechaMovimiento']);
            $row++;
        }
    
        // Descargar
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteMovimientoPlaca.xlsx"');
        header('Cache-Control: max-age=0');
    
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    
    
}

?>