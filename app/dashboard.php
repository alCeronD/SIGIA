<?php
require_once __DIR__ .'/helpers/session.php';
require_once __DIR__ .'/helpers/ScanFiles.php';
require_once __DIR__ .'/helpers/getUrl.php';
$modulo = $_GET['modulo'] ?? 'dashboard';
$controllerFile = new ScanFiles($modulo);
$css = $controllerFile->addUrl($modulo);


if ($css) {
    $_SESSION['css'] = $css;
} else {
    unset($_SESSION['css']);
}
require_once '../public/partials/header.php'; 
?>

<div class="container">
    <?php resolve(); ?>
</div>

<?phP require_once '../public/partials/footer.php'; ?>