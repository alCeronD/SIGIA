<?php

require_once '../app/helpers/session.php';
require_once '../app/helpers/ScanFiles.php';
$modulo = $_GET['modulo'] ?? 'dashboard';
$controllerFile = new ScanFiles($modulo);

// $css = $controllerFile->addUrl();
$css = $controllerFile->addUrl($modulo);


/**
 * TODO: separar las responsabilidades y primero definir el css.
 */


if ($css) {
    $_SESSION['css'] = $css;
} else {
    unset($_SESSION['css']);
}
$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_id'];

include '../public/partials/header.php';
?>

<div class="container">
    <?php resolve(); ?>
</div>

<?php
require_once '../public/partials/footer.php';
?>