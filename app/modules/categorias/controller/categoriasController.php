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
    
    public function createCategoria(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->conn->connect_error) {
                die("Error de conexión: " . $this->conn->connect_error);
            }
    
            $modeloCategoria = new categorias($this->conn);
    
            $datos = [
                'ca_nombre' => $_POST['ca_nombre'],
                'ca_descripcion' => $_POST['ca_descripcion'],
                'ca_status' => 1  
            ];
    
            $resultado = $modeloCategoria->create($datos);
    
            if ($resultado === true) {
                echo "<script>alert('Categoría registrada exitosamente'); window.location.href = '" . getUrl('categorias','categorias','categoriaView',false,'dashboard') . "';</script>";
            } else {
                echo $resultado;
            }
        }
    }



    
    public function updateCategoria(){
        
        $info = $_POST;
        $dato = new categorias($this->conn);
        $dato->update($info,$info['ca_id']);
        
        $modeloUsuarios = new categorias($this->conn);
        $resultado = $modeloUsuarios->search();
        
        if ($resultado) {
            // dd("estoy aqui");
            $this->consultCategoriasView();
           
            // return include_once __DIR__ . '/../views/categoriasConsultview.php';
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