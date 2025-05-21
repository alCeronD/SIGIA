<?php 

include_once 'modules/solicitudPrestamos/controller/solicitudPrestamoController.php';

$sltPrestamos = new solicitudController();

$mapfiles = new RenderView();

//$mapfiles::mapAssets('js','prestamos.js');

?>
<?php
include_once '../public/partials/header.php';
?>

  <?php 
   
    if ($sltPrestamos instanceof solicitudController) {
     $sltPrestamos::prestamos('solicitud');
    }
    ?>

<?php 
include_once '../public/partials/footer.php';
?>
