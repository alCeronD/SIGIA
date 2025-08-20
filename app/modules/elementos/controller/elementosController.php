<?php
include_once __DIR__ . '/../model/elementosModel.php';
require_once __DIR__ . '/../../configModules/model/configModulesModel.php';
// require_once __DIR__ . '/../../../helpers/response.php';
require_once __DIR__ . '/../../../helpers/const.php';
require_once __DIR__ . '/../../../helpers/validatePermisos.php';

class ElementosController
{
    private ElementoModelo $modeloElemento;
    private Conection $conn;

    public function __construct()
    {
        $this->modeloElemento = new ElementoModelo();
    }

    public function renderViewElements()
    {

        return require_once __DIR__ . '/../views/elementosView.php';
    }
    // Traer todos los elementos.
    public function getElements(int $pages = 1, String $type = 'all', bool $isBusqueda = false, String $value = "")
    {

        // Parámetros de paginación
        $pagina = isset($pages) ? max(1, intval($pages)) : 1;
        $limite = LIMIT;
        $offset = ($pagina - 1) * LIMIT;

        // Contar total de elementos para el paginador
        if ($isBusqueda) {
            $resultElements = $this->modeloElemento->contarElementosBusqueda($type, $value);
        } else {
            $resultElements = $this->modeloElemento->contarElementos($type);
        }
        $totalElementos = $resultElements['total'];
        $totalPaginas = ceil($totalElementos / $limite);

        // Obtener elementos paginados
        $elementos = $this->modeloElemento->obtenerElementoPaginado($limite, $offset, $type, $isBusqueda, $value);

        if (!$elementos) {
            fail('error al traer los elementos');
        }

        //Unifico ambos arreglos para obtener la cantidad de paginas con los elementos.
        $elementos = array_merge(['cantidadPaginas' => $totalPaginas], $elementos);

        success('elementos', $elementos);
    }

    // Traer el elemento por medio de busqueda
    public function getElement(String $value = '')
    {
        if (!$resultRow = $this->modeloElemento->getElementLike($value)) {
            fail('sin registros');
        }
        success('', $resultRow);
    }
    public function getPlacas(String $value = '')
    {
        $data = $this->modeloElemento->getAllPlacas();
        if (!$data) {
            fail('no hay registros', $data);
        }
        success('placas y seriales', $data);
    }
    //agregar elemento a la bd.
    public function addElement(array $data = [])
    {
        validatePermisos('elementos', 'addElement');

        foreach ($data as $key => $value) {

            // Valido si no se ha enviado nada en la serie para establecerla como NULL.
            if ($key == 'elm_serie' && empty($value))  $data['elm_serie'] = null;
            if($key == 'elm_categoria' && empty($value)) $data['elm_categoria'] = (int) 1;
            if($key == 'elm_ma_cod' && empty($value)) $data['elm_ma_cod'] = (int) 1;
        }

        if (!$result = $this->modeloElemento->insertarElemento($data)) {
            fail('error al ejecuutar proceso', $result);
        }
        success('registro adicionado con exito', $result);

    }

    public function getItems(String $action = '')
    {
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

    public function editarElemento(array $data = [])
    {
        validatePermisos('elementos', 'editarElemento');
        // Llamar al modelo para actualizar
        $exito = $this->modeloElemento->actualizarElemento($data);
        if (!$exito) {
            fail('error al procesar actualización', $exito);
        }
        success('recurso actualizado con exito', $exito);
    }

    public function cambiarEstadoElemento(array $data = [])
    {
        validatePermisos('elementos', 'cambiarEstadoElemento');
        if (isset($data['elm_cod']) && isset($data['elm_cod_estado'])) {
            $cod = (int) $data['elm_cod'];
            $id = (int) $data['elm_cod_estado'];
            $exito = $this->modeloElemento->toggleEstadoElemento($cod, $id);

            if (!$exito) {
                fail('Error al actualizar el elemento', $exito);
            }
            success('recurso actualizado', $exito);
        } else {
            // en caso de quie no se mande ningun elemento, devolver respuesta.
            return;
        }
    }

    public function editarExistencia(array $data = [])
    {
        validatePermisos('elementos', 'editarExistencia');
        $result = $this->modeloElemento->cambiarExistencia($data);
        if (!$result) {
            fail($result['message']);
        }

        success($result['message'], $result);
    }

    /**
     * Summary of getResultValidateSerie - Función de controlador para capturar la respuesta de validar la disponibilidad de la serie digitada por el usuario, devolver respuesta al usuario, true, significa que existe el elemento en la base de datos, por ende, no debe de registrarse con esa serie, false si es false, no devolver nada.
     * @param string $data
     * @return void
     */
    public function getResultValidateSerie (String $serie = ""){
        if(!$serie) fail('valor No definido', []);

        $result = $this->modeloElemento->validateSerie($serie);
        // Si el estatus es true, significa que si hay dato en la base de datos, y por ende, debe de mostrar mensaje de alerta indicando que no debe de registrar una serie ya existente.
        //TODO: debo de modificar la forma de aplicar los response.
        if ($result['status']) {

            $response = [
                'status' => false,
                'message'=> "La serie ".$result['data']." ya está registrada en la base de datos",
                'data' => $result
            ];
            http_response_code(200);
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit();
        }else{
            $response = [
                'status' => true,
                'message' => 'Esta serie ya está registrada en la base de datos',
                'data' => $result
            ];
            http_response_code(204);
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit();
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
                $isBusqueda = (empty($_GET['isBusqueda'])) ? null :(( $_GET['isBusqueda'] )== "true" ? true : false);
                $value = (String) (empty($_GET['value'])) ? null : $_GET['value'];


                if (method_exists($elementosController, 'getElements')) {
                    if (!$isBusqueda) {
                        $elementosController->getElements(
                            pages: $pages,
                            type: $type
                        );
                        
                    }else{
                        $elementosController->getElements($pages, $type, $isBusqueda, $value);

                    }

                }
                break;
            case 'onlyElement':
                $valueInput = strtolower($_GET['valueInput']);
                if (method_exists($elementosController, 'getElement')) {
                    $elementosController->getElement($valueInput);
                }

                break;

            case 'areas':

                if (method_exists($elementosController, 'getItems')) {
                    $elementosController->getItems($case);
                }

                break;
            case 'categoria':

                if (method_exists($elementosController, 'getItems')) {
                    $elementosController->getItems($case);
                }

                break;

            case 'marcas':
                if (method_exists($elementosController, 'getItems')) {
                    $elementosController->getItems($case);
                }
                break;

            case 'placas':
                if (method_exists($elementosController, 'getPlacas')) {
                    $elementosController->getPlacas($case);
                }

                break;


                case 'validateSerie':
                    $serie = (String) $_GET['data'];
                    if (method_exists($elementosController, 'getResultValidateSerie')) {
                        $elementosController->getResultValidateSerie($serie);
                    }
                    break;


            default:
                fail('error de acción.');
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = file_get_contents("php://input");

        //TODO: validar si data llego bien, en caso de que no, devolver un error 500.
        $data = json_decode($input, true);

        $action = $data['action'];
        unset($data['action']);

        switch ($action) {

            case 'registrar':
                $elemento = $data;
                if (method_exists($elementosController, 'addElement')) {
                    $elementosController->addElement($elemento);
                }
                break;

            default:
                break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $input = file_get_contents("php://input");

        //TODO: validar si data llego bien, en caso de que no, devolver un error 500.
        $data = json_decode($input, true);

        $action = $data['action'];
        unset($data['action']);


        switch ($action) {
            case 'updateElement':
                // var_dump($data);
                if (method_exists($elementosController, 'editarElemento')) {
                    $elementosController->editarElemento($data);
                }
                break;
            case 'statusElement':
                if (method_exists($elementosController, 'cambiarEstadoElemento')) {
                    $elementosController->cambiarEstadoElemento($data);
                }
                break;
            case 'ChangeExistencia':
                if (method_exists($elementosController, 'editarExistencia')) {
                    $elementosController->editarExistencia($data);
                }
                break;
            default:
                # code...
                break;
        }
    }
    exit();
}
