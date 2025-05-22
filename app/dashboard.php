<?php

include_once 'modules/solicitudPrestamos/controller/solicitudPrestamoController.php';
$sltPrestamos = new solicitudController();

$mapfiles = new RenderView();

?>
<?php
include_once '../public/partials/header.php';
?>

  <?php

  //Dejar por defecto la vista principal
  $case = isset($_GET['value']) ? $_GET['value'] : '5';
  $values = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

  //TODO: CREAR VALIDADOR SEGÚN LOS VALORES DEL MENÚ.
  if (in_array($case, $values)) {
    $value = $case;
  }

  switch ($value) {
    //Modulo prestamos
    case '3':
      if ($sltPrestamos instanceof solicitudController) {
        $sltPrestamos::prestamos('solicitud');
      }
      break;
    //Caso para ejecutar el modulo de prestamos.
    case '4':

      if ($sltPrestamos instanceof solicitudController) {
        $sltPrestamos::prestamos('consulta');
      }
      break;

    case '5':
      $mapfiles->renderView('dashBoard', 'viewDashboard.php');
      break;

    //Tipo documento
    case '6':
      $mapfiles->renderView('configModules', 'tpDocumentoView.php');
      break;

      //Area
    case '7':
      $mapfiles->renderView('configModules','areaView.php');
      break;
    default:

      break;
  }

  ?>

<?php
include_once '../public/partials/footer.php';
exit();
?>
