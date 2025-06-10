<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>

<h2 class="mb-4 text-center">Listado de Elementos</h2>
<a href="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" class="btn btn-primary mb-3">Registrar Nuevo Elemento</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Código</th>
            <th>Placa</th>
            <th>Nombre</th>
            <th>Existencia</th>
            <th>Unidad de Medida</th>
            <th>Tipo de Elemento</th>
            <th>Estado</th>
            <th>Área</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($elementos)) : ?>
            <?php foreach ($elementos as $elemento) : ?>
                <tr>
                    <td><?= $elemento['elm_cod']; ?></td>
                    <td><?= $elemento['elm_placa']; ?></td>
                    <td><?= $elemento['elm_nombre']; ?></td>
                    <td><?= $elemento['elm_existencia']; ?></td>
                    <td><?= $elemento['elm_uni_medida']; ?></td>
                    <td><?= $elemento['elm_cod_tp_elemento']; ?></td>
                    <td><?= $elemento['elm_cod_estado']; ?></td>
                    <td><?= $elemento['elm_area_cod']; ?></td>
                    <td>
                        <a href="<?= getUrl('elementos', 'elementos', 'editarElemento', ['elm_cod' => $elemento['elm_cod']], 'dashboard') ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="<?= getUrl('elementos', 'elementos', 'eliminarElemento', ['elm_cod' => $elemento['elm_cod']], 'dashboard') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este elemento?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="9" class="text-center">No hay elementos registrados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
