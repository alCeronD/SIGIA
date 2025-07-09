<?php
require_once __DIR__ .'/helpers/getUrl.php';
require_once __DIR__ .'/helpers/session.php';
require_once __DIR__ .'/helpers/ScanFiles.php';
$modulo = $_GET['modulo'] ?? 'dashboard';
$controllerFile = new ScanFiles($modulo);
$css = $controllerFile->addUrl($modulo);

if (ajaxGeneral()) {
    resolve();
    exit;
}



if ($css) {
    $_SESSION['css'] = $css;
} else {
    unset($_SESSION['css']);
}

require_once '../public/partials/header.php'; 
?>

<div class="container bg-light-pattern">
    <?php resolve(); ?>
</div>

<?php require_once '../public/partials/footer.php'; ?>