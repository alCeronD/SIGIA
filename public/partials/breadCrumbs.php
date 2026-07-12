<nav class="breadCrumbs" id="breadCrumbs">
  <div class="nav-wrapper">
    <div class="col s12">
      <?php
      $actualFunction = UtilsFunctions::getActualFunction();
      if (!empty($breadCrumbData)) {
        foreach ($breadCrumbData as $key => $value) {
          if ($actualFunction !== $value['key']) { ?>
            <a class="breadcrumb" href="<?php echo $value['url'] ?>"><?php echo $value['label']; ?></a>

          <?php } else { ?>
            <a class="breadcrumb"><?php echo $value['label']; ?></a>
      <?php
          }
        }
      }

      ?>

    </div>
  </div>
</nav>