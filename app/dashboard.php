<?php 

// session_start();
include_once '../app/helpers/getUrl.php';

// if (!isset($_SESSION['usuario'])) {
//     header("Location: /proyecto_sigia/index.php");
//     exit();
// }

require_once '../app/helpers/session.php';
$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];

// $data = !isset($data) ? getUrl('dashboard','dashboard','dashboard') : $data ;
//$data = 'modules/dashboard/views/dashboardView.php';
 //$_GET['modulo'] = 'dashboard';
 //$_GET['funcion'] = 'dashboard';
 //$_GET['controlador'] = 'dashboard';



// var_dump($resolve);


?>

<?php include '../public/partials/header.php'; ?>

<div class="container">
    <?php resolve(); ?>

</div>

<!-- include('../public/partials/body.php'); -->
<?php 
include'../public/partials/footer.php';
?>
