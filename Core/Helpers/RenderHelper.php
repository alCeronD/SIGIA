<?php
// clase para renderizar las vistas secundarias de los modulos.

class RenderHelper
{

  public static function renderSecondView(String $nombreModulo = "")
  {

    $menuSecond = $_SESSION['renderMenu']['menuSecondView'] ?? [];
    if (!empty($menuSecond)) {
      foreach ($menuSecond as $item) {
        if ($item['nombreModulo'] === $nombreModulo) {
?>
          <div class="option-card z-depth-1 div4">
            <div class="icons">
              <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>">
                <i class="material-icons green-text text-darken-2">
                  <?= htmlspecialchars($item['icono'], ENT_QUOTES, 'UTF-8'); ?>
                </i>
              </a>
            </div>
            <div class="modalName">
              <h5><?= htmlspecialchars($item['labelFuncion'], ENT_QUOTES, 'UTF-8'); ?></h5>
              <?php if (!empty($item['descripcion'])): ?>
                <p><?= htmlspecialchars($item['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>
          </div>

      <?php
        }
      }
    } else { ?>
      <p>No tienes accesos configurados para este módulo.</p>
    <?php } ?>
    <?php
  }

  public static function renderMainView()
  {
    $menuMain = $_SESSION['renderMenu']['menuMainView'];

    foreach ($menuMain as $key => $value) { ?>
      <div class="cardModules">
        <div class="contentModule">
          <a href="<?php echo $value['url']; ?>" class="linkIcon">
            <i class="material-icons green-text text-darken-2"><?php echo $value['icono']; ?>
            </i>
          </a>
          <div class="nameModule"><?php echo $value['titleModule']; ?></div>
        </div>
      </div>
<?php }
  }
}
