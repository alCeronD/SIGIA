
<style>
  .container {
    max-width: 900px;
    margin: 20px auto;
    padding: 10px;
  }

  .selector-container {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .selector-container label {
    font-weight: bold;
    font-size: 16px;
  }

  #tipo_selector {
    width: 250px;
    padding: 5px;
    font-size: 14px;
  }

  .form-section {
    display: none;
    margin-top: 10px;
  }

  .form-section.active {
    display: block;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  label {
    font-weight: bold;
  }

  input, select {
    padding: 6px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
  }

  .buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 12px;
  }



  .container {
    max-width: 900px;
    margin: 20px auto;
    padding: 10px;
  }

  .selector-container {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .selector-container label {
    font-weight: bold;
    font-size: 16px;
  }

  #tipo_selector {
    width: 250px;
    padding: 5px;
    font-size: 14px;
  }

  .form-section {
    display: none;
    margin-top: 10px;
  }

  .form-section.active {
    display: block;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  label {
    font-weight: bold;
  }

  input, select {
    padding: 6px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
  }

  .buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 12px;
  }
</style>

<?php
// Buscar el código del área "general" en $areas
$area_general_codigo = null;
foreach ($areas as $area) {
    if (strtolower(trim($area['nombre'])) === 'general') {
        $area_general_codigo = $area['codigo'];
        break;
    }
}
?>

<div class="selector-container">
  <label for="tipo_selector">Tipo de Elemento:</label>
  <select id="tipo_selector">
    <option value="">Seleccione...</option>
    <option value="devolutivo">Elemento Devolutivo</option>
    <option value="consumible">Elemento Consumible</option>
  </select>
</div>

<!-- Formulario Devolutivo -->
<div id="form_devolutivo" class="form-section">
  <form action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST">
    <input type="hidden" name="elm_cod_tp_elemento" id="tipo_devolutivo" value="1">
    <input type="hidden" name="elm_existencia" value="1">
    <input type="hidden" name="elm_cod_estado" value="1">

    <label for="elm_placa">Placa</label>
    <input type="number" name="elm_placa" id="elm_placa">

    <label for="elm_nombre">Nombre</label>
    <input type="text" name="elm_nombre" id="elm_nombre">

    <label for="elm_uni_medida">Unidad de Medida</label>
    <select name="elm_uni_medida" id="elm_uni_medida">
      <option value="">Seleccione...</option>
      <option value="1">Unidad</option>
      <option value="2">Caja</option>
      <option value="3">Paquete</option>
    </select>

    <label for="elm_area_cod">Área</label>
    <select name="elm_area_cod" id="elm_area_cod">
      <option value="">Seleccione...</option>
      <?php foreach ($areas as $area): ?>
        <option value="<?= $area['codigo'] ?>"><?= htmlspecialchars($area['nombre']) ?></option>
      <?php endforeach; ?>
    </select>

    <div class="buttons">
      <button type="submit">Guardar</button>
      <a href="<?= getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') ?>">Cancelar</a>
    </div>
  </form>
</div>

<!-- Formulario Consumible -->
<div id="form_consumible" class="form-section">
  <form action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST">
    <input type="hidden" name="elm_cod_tp_elemento" id="tipo_consumible" value="2">
    <input type="hidden" name="elm_cod_estado" value="1">

    <label for="elm_placa">Placa</label>
    <input type="number" name="elm_placa" id="elm_placa_c">

    <label for="elm_nombre">Nombre</label>
    <input type="text" name="elm_nombre" id="elm_nombre_c">

    <label for="elm_existencia">Cantidad a Agregar</label>
    <input type="number" name="elm_existencia" id="cantidad" min="1">

    <label for="elm_uni_medida">Unidad de Medida</label>
    <select name="elm_uni_medida" id="elm_uni_medida_c">
      <option value="">Seleccione...</option>
      <option value="1">Unidad</option>
      <option value="2">Caja</option>
      <option value="3">Paquete</option>
    </select>

    <label for="elm_area_cod_c">Área</label>
    <select name="elm_area_cod_c_disabled" id="elm_area_cod_c" disabled>
      <?php foreach ($areas as $area): ?>
        <option value="<?= $area['codigo'] ?>" <?= ($area['codigo'] == $area_general_codigo) ? 'selected' : '' ?>>
          <?= htmlspecialchars($area['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="hidden" name="elm_area_cod" value="<?= $area_general_codigo ?>">

    <div class="buttons">
      <button type="submit">Agregar</button>
    </div>
  </form>
</div>

<script>
  const selector = document.getElementById('tipo_selector');
  const formDevolutivo = document.getElementById('form_devolutivo');
  const formConsumible = document.getElementById('form_consumible');

  selector.addEventListener('change', function () {
    formDevolutivo.classList.remove('active');
    formConsumible.classList.remove('active');

    if (this.value === 'devolutivo') {
      formDevolutivo.classList.add('active');
    } else if (this.value === 'consumible') {
      formConsumible.classList.add('active');
    }
  });
</script>
