<h2 class="text-center mb-4">Editar Elemento</h2>
<form action="<?= getUrl('elementos', 'elementos', 'editarElemento', false, 'dashboard') ?>" method="POST">
    <input type="hidden" name="elm_cod" value="<?= $elemento['elm_cod'] ?>">

    <div class="mb-3">
        <label for="elm_placa" class="form-label">Placa</label>
        <input type="number" class="form-control" name="elm_placa" value="<?= $elemento['elm_placa'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="elm_nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" name="elm_nombre" value="<?= $elemento['elm_nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="elm_existencia" class="form-label">Existencia</label>
        <input type="number" class="form-control" name="elm_existencia" value="<?= $elemento['elm_existencia'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="elm_uni_medida" class="form-label">Unidad de Medida</label>
        <input type="number" class="form-control" name="elm_uni_medida" value="<?= $elemento['elm_uni_medida'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="elm_cod_tp_elemento" class="form-label">Tipo de Elemento</label>
        <input type="number" class="form-control" name="elm_cod_tp_elemento" value="<?= $elemento['elm_cod_tp_elemento'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="elm_cod_estado" class="form-label">Estado</label>
        <select class="form-select" name="elm_cod_estado">
            <option value="1" <?= $elemento['elm_cod_estado'] == 1 ? 'selected' : '' ?>>Activo</option>
            <option value="2" <?= $elemento['elm_cod_estado'] == 2 ? 'selected' : '' ?>>Inactivo</option>
            <option value="3" <?= $elemento['elm_cod_estado'] == 3 ? 'selected' : '' ?>>Dañado</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="elm_area_cod" class="form-label">Área</label>
        <input type="number" class="form-control" name="elm_area_cod" value="<?= $elemento['elm_area_cod'] ?>" required>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="<?= getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
