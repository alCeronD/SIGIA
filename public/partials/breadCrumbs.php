<nav class="breadCrumbs" id="breadCrumbs">
  <div class="nav-wrapper">
    <div class="col s12">
      <a href="<?php echo $modulesAndFunctions['Dashboard']['dashboard']; ?>" class="breadcrumb"><?php echo $routesPlaceHolders['Dashboard']['dashboard']; ?></a>

      <?php
      $actualFunction = UtilsFunctions::getActualFunction();
      $actualModule = UtilsFunctions::getActualModule();
      // IMPLEMENTAR RASTO DE MIGA PARA AQUELLOS QUE TIENEN VISTA ADICIONAL.
      if (!empty($setRoutes)) {
        foreach ($setRoutes as $key => $value) {
          // validamos si la function actual es diferente a la que esta en la ruta primaria, en caso de que sea diferente entonces renderizamos el rastro de miga pero con el link para re direccionar, en caso de que no sea asi, renderizamos solo el texto
          if ($actualFunction != $value['namePrimaryFunction']) { ?>
            <a class="breadcrumb" href="<?php echo $value['url']; ?>"><?php echo $value['placeholder']; ?></a>
          <?php } else { ?>
            <a class="breadcrumb"><?php echo $value['placeholder']; ?></a>
      <?php }
        }
      }
      ?>

      <!-- Aca implementar las otras vistas directas del usuario -->
      <?php
      foreach ($routesPlaceHolders as $key => $value) {
        if ($key === $actualModule) {
          // validamos que la funcion actual se encuentre en las funciones del arreglo
          if (isset($value[$actualFunction])) { ?>
            <!-- renderizamos el nombre de la vista actual PERO SIN direccionamiento -->
            <a class="breadcrumb"><?php echo $value[$actualFunction]; ?></a>
      <?php }
        }
      }
      ?>
    </div>
  </div>
</nav>