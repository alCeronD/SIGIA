<script src="/proyecto_sigia/public/assets/libraries/materialize/js/materialize.min.js"></script>

<?php
// Variables para validar el menú del usuario.
$modulos = $_SESSION['renderMenu']['modulos'];
$vistasModulos = $_SESSION['renderMenu']['vistas'];
// var_dump($vistasModulos);

?>
<?php //if ($rol == 2 || $rol == 4 || $rol == 16): 
?>

<div class="fixed-action-btn direction-top">
  <!-- Botón flotante principal del menú -->
  <a class="btn-floating btn-large teal darken-2 tooltipped" data-position="left" data-tooltip="Menú principal">
    <i class="material-icons">menu</i>
  </a>
  <ul>

    <?php foreach ($vistasModulos as $key => $value) : 
      ?>
      <?php $nombreModulo = $value['nombreModulo']; ?>
      <?php $funcionModulo = $value['nombreFuncionController']; ?>
      <li>
        <a href="<?php echo getUrl($nombreModulo,$nombreModulo,$funcionModulo,false,'dashboard'); ?>" class="btn-floating red " data-tooltip="">
          <i class="material-icons">exit_to_app</i>
        </a>
      </li>
      
    <?php endforeach; ?>
    <li>
      <a href="<?php echo getUrl('login','login','logout'); ?>" class="btn-floating red " data-tooltip="Cerrar sesión">
          <i class="material-icons">exit_to_app</i>
      </a>
      </li>

  </ul>
</div>

<style>
  .submenu {
    position: absolute;
    background: white;
    padding: 12px 16px;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    z-index: 999;
    min-width: 180px;
    max-height: 190px;
    overflow-y: auto;
  }

  .submenu a {
    padding: 6px 10px;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
  }

  .submenu a:hover {
    background: #f2f2f2;
  }

  .submenu.visible {
    display: flex;
  }

  .hidden {
    display: none;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const menu = document.querySelectorAll('.fixed-action-btn');
    M.FloatingActionButton.init(menu, {
      direction: 'top',
      hoverEnabled: false
    });

    console.log(menu);


    const triggers = document.querySelectorAll('.submenu-trigger');
    triggers.forEach(trigger => {
      const submenuId = trigger.dataset.submenu;
      const submenu = document.getElementById(submenuId);

      trigger.addEventListener('mouseenter', () => {
        document.querySelectorAll('.submenu').forEach(el => el.classList.remove('visible'));

        const rect = trigger.getBoundingClientRect();
        const submenuHeight = submenu.offsetHeight || 150;
        submenu.style.top = `${rect.top + window.scrollY  }px`;
        submenu.style.left = `${rect.left - submenu.offsetWidth - 190}px`; //Ajusto menu <<
        submenu.classList.add('visible');
      });

      trigger.addEventListener('mouseleave', () => {
        setTimeout(() => {
          if (!submenu.matches(':hover') && !trigger.matches(':hover')) {
            submenu.classList.remove('visible');
          }
        }, 200);
      });


      submenu.addEventListener('mouseleave', () => submenu.classList.remove('visible'));
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.fixed-action-btn') && !e.target.closest('.submenu')) {
        document.querySelectorAll('.submenu').forEach(el => el.classList.remove('visible'));
      }
    });
  });
</script>


</body>

</html>