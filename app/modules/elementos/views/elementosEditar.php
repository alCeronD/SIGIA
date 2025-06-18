<style>
    .form-wrapper {
        display: flex;
        justify-content: center;
        padding-top: 40px;
    }

    .editar-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        width: 700px;
    }

    .editar-form h2 {
        grid-column: span 2;
        text-align: center;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-actions {
        grid-column: span 2;
        display: flex;
        justify-content: center;
        gap: 20px;
    }
</style>

<div class="form-wrapper">
    <form class="editar-form" action="<?= getUrl('elementos', 'elementos', 'editarElemento', false, 'dashboard') ?>" method="POST">
        <h2>Editar Elemento</h2>

        <input type="hidden" name="elm_cod" value="<?= $elemento['elm_cod'] ?>">
        <input type="hidden" name="elm_cod_tp_elemento" value="<?= $elemento['elm_cod_tp_elemento'] ?>">

        <!-- Placa (No modificable) -->
        <div class="form-group">
            <label>Placa</label>
            <label><?= $elemento['elm_placa'] ?></label>
        </div>

        <!-- Nombre (Modificable) -->
        <div class="form-group">
            <label for="elm_nombre">Nombre</label>
            <input type="text" id="elm_nombre" name="elm_nombre" value="<?= $elemento['elm_nombre'] ?>" required>
        </div>

        <!-- Existencia (No modificable) -->
        <div class="form-group">
            <label>Existencia</label>
            <label><?= $elemento['elm_existencia'] ?></label>
        </div>

        <!-- Unidad de Medida (Modificable) -->
        <div class="form-group">
            <label for="elm_uni_medida">Unidad de Medida</label>
            <input type="number" id="elm_uni_medida" name="elm_uni_medida" value="<?= $elemento['elm_uni_medida'] ?>" required>
        </div>

        <!-- Tipo de Elemento (No modificable) -->
        <div class="form-group">
            <label>Tipo de Elemento</label>
            <label><?= $elemento['tipoElemento'] ?></label>
        </div>

        <!-- Área (Modificable) -->
        <div class="form-group">
            <label for="elm_area_cod">Área</label>
            <select id="elm_area_cod" name="elm_area_cod" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?= $area['codigo'] ?>" <?= ($area['codigo'] == $elemento['elm_area_cod']) ? 'selected' : '' ?>>
                        <?= $area['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <button type="submit">Guardar Cambios</button>
            <a href="<?= getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') ?>">Cancelar</a>
        </div>
    </form>
</div>
