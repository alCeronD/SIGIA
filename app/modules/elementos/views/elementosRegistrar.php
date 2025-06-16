<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>

<h2 class="mb-4 text-center">Registrar Nuevo Elemento</h2>

<form action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST">
    <div class="mb-3">
        <label for="elm_placa" class="form-label">Placa</label>
        <input type="number" name="elm_placa" id="elm_placa" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="elm_nombre" class="form-label">Nombre</label>
        <input type="text" name="elm_nombre" id="elm_nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="elm_existencia" class="form-label">Existencia</label>
        <input type="number" name="elm_existencia" id="elm_existencia" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="elm_uni_medida" class="form-label">Unidad de Medida</label>
        <select name="elm_uni_medida" id="elm_uni_medida" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="1">Unidad</option>
            <option value="2">Caja</option>
            <option value="3">Paquete</option>
            <!-- Agrega más si es necesario -->
        </select>
    </div>

    <div class="mb-3">
        <label for="elm_cod_tp_elemento" class="form-label">Tipo de Elemento</label>
        <select name="elm_cod_tp_elemento" id="elm_cod_tp_elemento" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="1">Tecnológico</option>
            <option value="2">Suministro</option>
            <!-- Ajusta según los valores reales de tu tabla -->
        </select>
    </div>

    <div class="mb-3">
        <label for="elm_cod_estado" class="form-label">Estado</label>
        <select name="elm_cod_estado" id="elm_cod_estado" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
            <option value="3">Dañado</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="elm_area_cod" class="form-label">Área</label>
        <select name="elm_area_cod" id="elm_area_cod" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="1">Producción</option>
            <option value="2">Fotografía</option>
            <option value="3">Diseño</option>
            <option value="4">Soporte</option>
            <!-- Agrega más si tienes otras áreas -->
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-success">Guardar Elemento</button>
        <a href="<?= getUrl('elementos', 'elementos', 'mostrarElementos', false, 'dashboard') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
