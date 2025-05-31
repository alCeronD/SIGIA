<?php 

// session_start();
// include_once '../app/helpers/getUrl.php';

require_once '../app/helpers/session.php';
$usuario = $_SESSION['usuario'];
// dd($usuario);
$rol = $usuario['rol_id'];


?>

<?php include '../public/partials/header.php'; ?>

<div class="container">
    <?php resolve(); ?>

</div>

<?php 
require_once '../public/partials/footer.php';
?>
