<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>

<style>
    
   /* Modal Ver Más: fijo y oculto al inicio */
#modalVerMas {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

#modalVerMas.show {
    display: flex !important;
}

#modalVerMas .modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

#modalVerMas .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    font-weight: bold;
    color: #000;
    cursor: pointer;
}

/* Contenedor principal con grid */
.container-fluid.px-4 {
    display: grid;
    grid-template-rows: auto auto 1fr;
    gap: 20px;
    height: 100vh;
    box-sizing: border-box;
}

/* Fila de filtros y botón registrar */
.row.mb-3 {
    display: grid;
    grid-template-columns: 1fr 2fr;
    align-items: center;
    gap: 20px;
}

/* Dentro de col-md-8: filtros alineados horizontalmente */
.row.mb-3 > .col-md-8 {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
}

/* Estilo base de inputs y select */
.form-select, .form-control {
    max-width: 200px;
}

/* Tabla con scroll fijo */
.table-responsive-fixed {
    overflow-x: auto;
    max-height: 70vh;
    position: relative;
}

/* Encabezados fijos */
thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 1;
}

/* Grupo de botones en una sola fila */
/* Grupo de botones en una sola fila */
.btn-group {
    display: flex;
    gap: 0px; /* Reducido para pegar más los botones */
    flex-wrap: nowrap;
}


</style>

<div class="container-fluid px-4">
    <h2 class="mb-4 text-center">Listado de Elementos</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <a href="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" class="btn btn-primary">Registrar Nuevo Elemento</a>
        </div>
        <div class="col-md-8 d-flex justify-content-end">
            <select class="form-select me-2" style="max-width: 200px;">
                <option selected>Todos</option>
                <option>Devolutivo</option>
                <option>Consumible</option>
            </select>
            <input type="text" class="form-control me-2" placeholder="Buscar..." style="max-width: 200px;">
            <button class="btn btn-outline-primary">Buscar</button>
        </div>
    </div>

    <div class="table-responsive table-responsive-fixed">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-light">
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
                            <td><?= $elemento['codigoElemento']; ?></td>
                            <td><?= $elemento['placa']; ?></td>
                            <td><?= $elemento['nombreElemento']; ?></td>
                            <td><?= $elemento['cantidad']; ?></td>
                            <td><?= $elemento['unidadMedida']; ?></td>
                            <td><?= $elemento['tipoElemento']; ?></td>
                            <td><?= $elemento['estadoElemento']; ?></td>
                            <td><?= $elemento['nombreArea']; ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= getUrl('elementos', 'elementos', 'editarElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>" class="btn btn-warning btn-sm">Editar</a>

                                    <?php
                                    $estado = strtolower(trim($elemento['estadoElemento'] ?? ''));

                                    if ($estado == 'disponible') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Está seguro de inhabilitar este elemento?');">Inhabilitar</a>

                                    <?php elseif ($estado == 'inhabilitado') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('¿Está seguro de activar este elemento?');">Activar</a>

                                    <?php else : ?>
                                        <span class="text-muted"><?= htmlspecialchars($estado) ?></span>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-info btn-sm btnVerMas" data-cod="<?= $elemento['codigoElemento'] ?>">Ver Más</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">No hay elementos registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ver Más -->
<div id="modalVerMas">
    <div class="modal-content">
        <span class="close-btn" id="modalCerrar">&times;</span>
        <h4>Detalles del Elemento</h4>
        <table class="table table-bordered">
            <tbody>
                <tr><th>Código</th><td id="modalCod"></td></tr>
                <tr><th>Placa</th><td id="modalPlaca"></td></tr>
                <tr><th>Nombre</th><td id="modalNombre"></td></tr>
                <tr><th>Existencia</th><td id="modalExistencia"></td></tr>
                <tr><th>Unidad de Medida</th><td id="modalUniMedida"></td></tr>
                <tr><th>Tipo de Elemento</th><td id="modalTipo"></td></tr>
                <tr><th>Estado</th><td id="modalEstado"></td></tr>
                <tr><th>Área</th><td id="modalArea"></td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.elementosData = <?= json_encode($elementos) ?>;
</script>
<script type="module" src="../public/assets/js/elementos/elementos.js"></script>
