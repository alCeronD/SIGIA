<?php
include_once __DIR__ . '/../model/categoriasModel.php';
include_once __DIR__ . '/../../../config/conn.php';
include_once __DIR__ . '/../../../helpers/validatePermisos.php';

class categoriasController
{

    public $ca_id;
    public $ca_nombre;
    public $ca_descripcion;
    public $ca_estatus;
    private $conn;

    public function __construct($conexion)
    {
        $this->conn = $conexion;
    }

    public function categoriaView()
    {
        $this->consultCategoriasView();
    }

    public function consultCategoriasView()
    {
        validatePermisos('categorias', 'consultCategoriasView');
        $modeloCategorias = new categorias($this->conn);
        $_SESSION['css'] = 'categorias/categorias.css';
        $categorias = $modeloCategorias->search();
        // $path = __DIR__ . '/../views/categoriasConsultview.php';
        $path = __DIR__ . '/../views/categoriasConsultview.php';
        return include $path;
    }

    public function updateCategoriaView()
    {
        validatePermisos('categorias', 'updateCategoriaView');

        $categoria = $_GET['ca_id'];
        $_SESSION['css'] = 'categorias/categorias.css';

        $dato = new categorias($this->conn);
        $resultado = $dato->searchU($categoria);
        if ($resultado) {
            return include_once __DIR__ . '/../views/categoriasEditView.php';
        }
    }

    public function updateCategoria()
    {
        validatePermisos('categorias', 'updateCategoria');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $categoriaModel = new categorias($this->conn);

            $datos = [
                'ca_id' => $_POST['ca_id'] ?? null,
                'ca_nombre' => $_POST['ca_nombre'] ?? '',
                'ca_descripcion' => $_POST['ca_descripcion'] ?? '',
                'ca_status' => $_POST['ca_status'] ?? 1
            ];

            $actualizado = $categoriaModel->update($datos, $datos['ca_id']);

            // Detectar si la petición es AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                header('Content-Type: application/json');

                if ($actualizado) {
                    $categoriaActualizada = $categoriaModel->findById($datos['ca_id']);
                    echo json_encode([
                        'success' => true,
                        'mensaje' => 'Registro actualizado correctamente.',
                        'categoria' => $categoriaActualizada
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'mensaje' => 'No se pudo actualizar el registro.'
                    ]);
                }
                exit();
            } else {
                // Redirección cuando no es AJAX
                if ($actualizado) {
                    $_SESSION['success'] = 'Registro actualizado correctamente.';
                } else {
                    $_SESSION['error'] = 'No se pudo actualizar el registro.';
                }

                header('Location: ' . getUrl('categorias', 'categorias', 'consultCategoriasView', false, 'dashboard'));
                exit();
            }
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ]);
            exit();
        }
    }



    public function createCategoria()
    {
        validatePermisos('categorias', 'createCategoria');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $modeloCategoria = new categorias($this->conn);

            $datos = [
                'ca_nombre' => $_POST['ca_nombre'] ?? '',
                'ca_descripcion' => $_POST['ca_descripcion'] ?? '',
                'ca_status' => 1
            ];

            $nuevoId = $modeloCategoria->create($datos);

            if (is_numeric($nuevoId)) {
                $nuevaCategoria = $modeloCategoria->findById($nuevoId);
                echo json_encode([
                    'success' => true,
                    'message' => 'Categoría creada correctamente',
                    'categoria' => $nuevaCategoria
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudo crear la categoría. Detalles: ' . $nuevoId
                ]);
            }
            exit();
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
        }
    }

    public function deleteCategoria()
    {
        $id = $_GET['ca_id'];
        $categorias = new categorias($this->conn);
        $dato = $categorias->delete($id);

        if ($dato) {
            $this->consultCategoriasView();
        }
    }
    
    public function listarCategoriasAjax()
    {
        validatePermisos('categorias', 'listarCategoriasAjax');

        // Validar y obtener parámetros de paginación
        $pagina = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $porPagina = 5;
        $offset = ($pagina - 1) * $porPagina;

        // Instanciar el modelo
        $modelo = new categorias($this->conn);

        // Obtener total y datos paginados
        $total = $modelo->contarTotal();
        $categorias = $modelo->listarPaginadas($offset, $porPagina);

        // Preparar y enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => [
                'categorias' => $categorias,
                'total' => $total,
                'paginaActual' => $pagina,
                'porPagina' => $porPagina
            ]
        ]);
    }
}
