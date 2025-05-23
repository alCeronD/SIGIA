<?php

//require_once 'helpers/renderView.php';
//$render = new RenderView();

//Controlador modulo solicitudPrestamos.
require_once 'modules/solicitudPrestamos/controller/solicitudPrestamoController.php';
$sltPrestamos = new solicitudController();

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
      $sltPrestamos = new solicitudController();
      if ($sltPrestamos instanceof solicitudController) {
        //Esto es una función estática en donde no retorna ningún valor, ahí se incluye la vista.
        $sltPrestamos::prestamos('consulta');
      }
      break;

    case '5':
      //$render->renderView('dashBoard', 'viewDashboard.php');
      break;

    //Tipo documento
    case '6':
      require_once '';
      //$render->renderView('configModules', 'tpDocumentoView.php');
      break;

      //Area
    case '7':
          require_once 'modules/configModules/controller/configModulesController.php';
          $configModulesController = new ConfigModulesController('configModules', 'areaView.php');
      $path = $configModulesController->render();
      //Incluyo la vista.
      require_once $path;
      break;
    default:
    //La idea de este default es que redireccione a login.

      break;
  }

  ?>

<?php
include_once '../public/partials/footer.php';
exit();
?>
