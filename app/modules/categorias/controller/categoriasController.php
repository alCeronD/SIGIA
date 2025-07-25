<?php
include_once __DIR__ . '/../model/categoriasModel.php';
include_once __DIR__ . '/../../../config/conn.php';


class categoriasController {

public $ca_id;
public $ca_nombre;
public $ca_descripcion;
public $ca_estatus;
private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }
    
    public function categoriaView() {
        $this->consultCategoriasView();
    }
    
    public function consultCategoriasView(){
        $modeloCategorias = new categorias($this->conn);
        $_SESSION['css'] = 'categorias/categorias.css';
        $categorias = $modeloCategorias->search();
        $path = __DIR__ . '/../views/categoriasConsultview.php';
        // var_dump($path);
        return include $path;
    }
    
    public function updateCategoriaView(){
        $categoria = $_GET['ca_id'];
        $_SESSION['css'] = 'categorias/categorias.css';
        
        $dato = new categorias($this->conn);
        $resultado = $dato->searchU($categoria);
        if ($resultado) {
            return include_once __DIR__ . '/../views/categoriasEditView.php';
        }
    }
    
    public function createCategoria() {
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->conn->connect_error) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Error de conexión: ' . $this->conn->connect_error
                ]);
                return;
            }
    
            $modeloCategoria = new categorias($this->conn);
    
            $datos = [
                'ca_nombre' => $_POST['ca_nombre'],
                'ca_descripcion' => $_POST['ca_descripcion'],
                'ca_status' => 1
            ];
    
            $resultado = $modeloCategoria->create($datos);
    
            if (is_array($resultado) && isset($resultado['ca_id'])) {
                $inserted_id = $this->conn->insert_id;
                $datos['ca_id'] = $inserted_id;
    
                echo json_encode([
                    'success' => true,
                    'categoria' => $datos
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'mensaje' => $resultado
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Método no permitido'
            ]);
        }
    }







    public function updateCategoria(){
        
        $info = $_POST;
        $dato = new categorias($this->conn);
        $dato->update($info,$info['ca_id']);
        
        $modeloUsuarios = new categorias($this->conn);
        $resultado = $modeloUsuarios->search();
        
        if ($resultado) {
            $this->consultCategoriasView();
                echo "<script>alert('Registro actualizado'); window.location.href = '" . getUrl('categorias','categorias','categoriaView',false,'dashboard') . "';</script>";
        }
    }

    public function deleteCategoria(){
        $id = $_GET['ca_id'];
        $categorias = new categorias($this->conn);
        $dato = $categorias->delete($id);
        
        if ($dato) {
            $this->consultCategoriasView();
        }
    }
}

?>