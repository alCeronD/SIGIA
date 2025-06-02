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

// dd("pero que pasaoooooo");
?>

<?php include '../public/partials/header.php'; ?>

<div class="container">
    <?php resolve(); ?>

</div>

<!-- include('../public/partials/body.php'); -->
<?php 
include'../public/partials/footer.php';
?>
