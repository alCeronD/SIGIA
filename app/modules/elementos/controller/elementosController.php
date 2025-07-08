<?php
include_once __DIR__ . '/../model/elementosModel.php';
require_once __DIR__ . '/../../configModules/model/configModulesModel.php';
require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/const.php';


class ElementosController
{
    private $modeloElemento;
    private $conn;

    public function __construct()
    {
        $this->modeloElemento = new ElementoModelo();
    }

    public function renderViewElements()
    {

        return require_once __DIR__ . '/../views/elementosView.php';
    }

    public function getElements(int $pages = 1, String $type = 'all')
    {
        // Parámetros de paginación
        $pagina = isset($pages) ? max(1, intval($pages)) : 1;
        $limite = LIMIT;
        $offset = ($pagina - 1) * LIMIT;

        // Contar total de elementos para el paginador
        $resultElements = $this->modeloElemento->contarElementos($type);
        $totalElementos = $resultElements['total'];
        $totalPaginas = ceil($totalElementos / $limite);



        // Obtener elementos paginados
        $elementos = $this->modeloElemento->obtenerElementoPaginado($limite, $offset, $type);


        if (!$elementos) {
            fail('error al traer los elementos');
        }

        //Unifico ambos arreglos para obtener la cantidad de paginas con los elementos.
        $elementos = array_merge(['cantidadPaginas' => $totalPaginas], $elementos);

        success('elementos', $elementos);
    }

    public function getElement(String $value = '')
    {
        if (!$resultRow = $this->modeloElemento->getElementLike($value)) {
            fail('sin registros');
        }
        success('', $resultRow);
    }

    public function getPlacas(String $value = ''){
        $this->modeloElemento->getAllPlacas();

    }

    public function registrarElemento()
    {
        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //     // Validar que existan todos los campos obligatorios
        //     if (
        //         isset($_POST['elm_placa'], $_POST['elm_nombre'], $_POST['elm_existencia'],
        //                $_POST['elm_uni_medida'], $_POST['elm_cod_tp_elemento'],
        //                $_POST['elm_cod_estado'], $_POST['elm_area_cod'])
        //     ) {
        //         $datos = [
        //             'elm_placa' => $_POST['elm_placa'],
        //             'elm_nombre' => $_POST['elm_nombre'],
        //             'elm_existencia' => $_POST['elm_existencia'],
        //             'elm_uni_medida' => $_POST['elm_uni_medida'],
        //             'elm_cod_tp_elemento' => $_POST['elm_cod_tp_elemento'],
        //             'elm_cod_estado' => $_POST['elm_cod_estado'],
        //             'elm_area_cod' => $_POST['elm_area_cod']
        //         ];

        //         $exito = $this->modeloElemento->insertarElemento($datos);

        //         if ($exito) {
        //             echo "<script>alert('Elemento registrado exitosamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
        //         } else {
        //             echo "<div class='alert alert-danger text-center'>Error al registrar el elemento.</div>";
        //         }
        //     } else {
        //         echo "<div class='alert alert-danger text-center'>Faltan datos obligatorios para registrar.</div>";
        //     }
        // } else {
        //     $modeloGenerico = new ConfigModulesModel();
        //     $areas = $modeloGenerico->select("SELECT ar_cod AS codigo, ar_nombre AS nombre FROM areas");
        //     include __DIR__ . '/../views/elementosRegistrar.php';
        // }
    }

    public function getItems(String $action = ''){
        // Obtener las áreas
        $modeloGenerico = new ConfigModulesModel();
        //Areas que esten activas.
        $items = $modeloGenerico->select("SELECT * FROM $action");

        $newData = [];

        foreach ($items as $item) {
            // Buscar clave *_status
            foreach ($item as $key => $value) {
                if (preg_match('/_status$/', $key)) {
                    if ((int) $value === 1) {
                        $newData[] = $item;
                    }
                    break;
                }
            }
        }
        success("registros de: $action", $newData);
        
    }

    public function editarElemento()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['elm_cod'], $_POST['elm_nombre'], $_POST['elm_uni_medida'], $_POST['elm_area_cod'])) {
                $id = $_POST['elm_cod'];

                // Llenar arreglo con los datos que sí se actualizan
                $datos = [
                    'elm_nombre' => $_POST['elm_nombre'],
                    'elm_uni_medida' => $_POST['elm_uni_medida'],
                    'elm_area_cod' => $_POST['elm_area_cod'],
                    'elm_cod_estado' => 1 // Siempre activo (o puedes recibirlo por POST si quieres que sea editable)
                ];

                // Llamar al modelo para actualizar
                $exito = $this->modeloElemento->actualizarElemento($id, $datos);

                if ($exito) {
                    echo "<script>alert('Elemento actualizado correctamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Error al actualizar el elemento.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Faltan datos obligatorios.</div>";
            }
        } else {
            // Mostrar formulario de edición
            if (isset($_GET['elm_cod'])) {
                $id = $_GET['elm_cod'];
                $elemento = $this->modeloElemento->obtenerElementoPorId($id);

                // Usamos el modelo genérico para obtener áreas
                $modeloGenerico = new ConfigModulesModel();
                $areas = $modeloGenerico->select("SELECT ar_cod AS codigo, ar_nombre AS nombre FROM areas");

                if ($elemento) {
                    include __DIR__ . '/../views/elementosEditar.php';
                } else {
                    echo "<div class='alert alert-danger text-center'>Elemento no encontrado.</div>";
                }
            }
        }
    }

    public function cambiarEstadoElemento()
    {
        if (isset($_GET['elm_cod'])) {
            $id = $_GET['elm_cod'];
            $exito = $this->modeloElemento->toggleEstadoElemento($id);

            if ($exito) {
                echo "<script>alert('Estado del elemento cambiado correctamente'); window.location.href = '" . getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') . "';</script>";
            } else {
                echo "<div class='alert alert-danger text-center'>Error al cambiar estado del elemento.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>No se especificó el elemento para cambiar estado.</div>";
        }
    }
}

$elementosController = new ElementosController();
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $case = $_GET['action'] ?? '';
        //valor de la página, por defecto, es la página #1.
        $pages = (int) ($_GET['pages'] ?? 1);

        // Esto se puede cambiar, en ves de switch case, arreglo con su calve y valor y ahí validar la información.
        switch ($case) {
            case 'elements':
                $type = $_GET['type'];
                if (method_exists($elementosController, 'getElements')) {
                    $elementosController->getElements($pages, $type);
                }
                break;
            case 'onlyElement':
                $valueInput = strtolower($_GET['valueInput']);
                if (method_exists($elementosController, 'getElement')) {
                    $elementosController->getElement($valueInput);
                }

                break;

            case 'areas':

                if (method_exists($elementosController,'getItems')) {
                    $elementosController->getItems($case);
                }

                break;
            case 'categoria':
                
                if (method_exists($elementosController,'getItems')) {
                    $elementosController->getItems($case);
                }
                
                break;

            case 'marcas':
                if (method_exists($elementosController,'getItems')) {
                    $elementosController->getItems($case);
                }
                break;

            case 'placas':
                if (method_exists($elementosController,'getPlacas')) {
                    $elementosController->getPlacas($case);
                }

                break;

            default:
                fail('error de acción.');
                break;
        }
    }

    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     $input = file_get_contents("php://input");

    //     //TODO: validar si data llego bien, en caso de que no, devolver un error 500.
    //     $data = json_decode($input, true);


    //     switch ($data['action']) {
    //         case 'finalizar':

    //             $elementos = $data['data']["elementos"];
    //             $codigoReserva = $data['data']["codigoReserva"];

    //             $controller->setEndReserva($elementos, $codigoReserva);
    //             break;

    //         case 'registrar':
    //             $elementosPres = $data['data'];
    //             $controller->setReserva($elementosPres);
    //             break;
    //         case 'validateLoan':
    //             unset($data['action']);
    //             $dataNuevo = $data;


    //             $controller->setSolicitud($dataNuevo);

    //             //la validación del data es practicamente el setReserva pero la hare en otra función por cuestión de tiempo.


    //         break;    
    //         default:
    //             break;
    //     }

    // }
    exit();
}
