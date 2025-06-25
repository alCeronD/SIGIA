<style>


/* ----------------- Contenedor General ----------------- */
.container-fluid.px-4 {
    display: grid;
    grid-template-rows: auto auto 1fr;
    gap: 20px;
    height: 100vh;
    box-sizing: border-box;
    padding: 20px;
    background-color: #f5f7fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ----------------- Título ----------------- */
.container-fluid h2 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

/* ----------------- Fila de Filtros y Botón ----------------- */
.row.mb-3 {
    display: grid;
    grid-template-columns: 1fr 2fr;
    align-items: center;
    gap: 20px;
}

.row.mb-3 > .col-md-8 {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
}

/* ----------------- Inputs y Select ----------------- */
.form-select,
.form-control {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* ----------------- Botón Estándar ----------------- */
.btn {
    padding: 8px 14px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn:hover {
    background-color: #0056b3;
}

/* ----------------- Tabla ----------------- */
.table-responsive-fixed {
    overflow-x: auto;
    max-height: 70vh;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: white;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead th {
    position: sticky;
    top: 0;
    background-color: #e9ecef;
    color: #333;
    padding: 10px;
    font-weight: 600;
    z-index: 1;
}

.table td,
.table th {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

/* ----------------- Botones de Acción ----------------- */
.btn-group {
    display: flex;
    gap: 5px;
    flex-wrap: nowrap;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 13px;
}

/* Colores personalizados para los botones por estado */
.btn-warning {
    background-color: #ffc107;
    color: #000;
}
.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}
.btn-danger:hover {
    background-color: #b52a37;
}

.btn-success {
    background-color: #28a745;
    color: #fff;
}
.btn-success:hover {
    background-color: #218838;
}

.btn-info {
    background-color: #17a2b8;
    color: #fff;
}
.btn-info:hover {
    background-color: #117a8b;
}

/* ----------------- Modal "Ver Más" ----------------- */
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
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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

/* Modal Table */
#modalVerMas table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

#modalVerMas th,
#modalVerMas td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

#modalVerMas th {
    background-color: #f1f1f1;
    font-weight: bold;
    width: 40%;
}


/* ---------- Ajuste de Ancho y Alineación del Formulario ---------- */

#modalRegistrar form {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  padding: 10px 20px;
  font-family: 'Segoe UI', sans-serif;
  background-color: #fafafa;
  border-radius: 10px;
  max-width: 500px;
  margin: 0 auto; /* Centrado horizontal */
}

/* Grupos de campos */
#modalRegistrar .mb-3 {
  display: grid;
  grid-template-columns: 1fr;
  gap: 6px;
}

/* Etiquetas */
#modalRegistrar label {
  font-weight: 600;
  font-size: 14px;
  color: #333;
}

/* Inputs y selects con ancho limitado */
#modalRegistrar input[type="text"],
#modalRegistrar input[type="number"],
#modalRegistrar select {
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fff;
  transition: border 0.3s ease, box-shadow 0.3s ease;
  max-width: 100%;
}

#modalRegistrar input[type="text"]:focus,
#modalRegistrar input[type="number"]:focus,
#modalRegistrar select:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
  outline: none;
}

/* Selector tipo de elemento */
#tipoElementoSelect {
  padding: 10px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fff;
  width: 82%;
  max-width: 500px;
  margin: 0 auto 16px auto; /* <-- Esto centra horizontalmente */
  display: block;
}


/* Botón guardar con ajuste de ancho */
#modalRegistrar button[type="submit"] {
  padding: 10px;
  width: 100%;
  max-width: 500px;
  margin: 10px auto 0;
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: white;
  font-weight: bold;
  font-size: 15px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
}

#modalRegistrar button[type="submit"]:hover {
  background: linear-gradient(135deg, #0056b3, #003e8c);
}


#filtroTipo {
  display: block !important;
  opacity: 1 !important;
  position: relative !important;
  z-index: 1000 !important;
  width: 200px !important;
  height: auto !important;
  background-color: white !important;
}

</style>

<div class="container-fluid px-4">
    <h2 class="mb-4 text-center">Listado de Elementos</h2>

    <div class="row mb-3">
        <div class="col-md-4">
        <button id="abrirModalRegistrar" class="btn btn-primary">Registrar Nuevo Elemento</button>
        </div>
        <div class="col-md-8 d-flex justify-content-end">
            <select id="filtroTipo" class="form-select me-2" style="max-width: 200px;">
                <option value="todos" selected>Todos</option>
                <option value="devolutivo">Devolutivo</option>
                <option value="consumible">Consumible</option>
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
                        <tr data-tipo="<?= strtolower($elemento['tipoElemento']) ?>">
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
                                    <a href="<?= getUrl('elementos', 'elementos', 'editarElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>" class="btn btn-warning btn-sm">✏️</a>

                                    <?php
                                    $estado = strtolower(trim($elemento['estadoElemento'] ?? ''));

                                    if ($estado == 'disponible') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Está seguro de inhabilitar este elemento?');">❌</a>

                                    <?php elseif ($estado == 'inhabilitado') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('¿Está seguro de activar este elemento?');">✅</a>

                                    <?php else : ?>
                                        <span class="text-muted"><?= htmlspecialchars($estado) ?></span>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-info btn-sm btnVerMas" data-cod="<?= $elemento['codigoElemento'] ?>">🔍</button>
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



<!-- Modal Registrar Elemento -->
<div id="modalRegistrar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div style="background:#fff; padding:20px; border-radius:8px; width:90%; max-width:600px; max-height:90vh; overflow-y:auto; position:relative;">
        <button id="cerrarModalRegistrar" style="position:absolute; top:10px; right:15px; font-size:24px; background:none; border:none; cursor:pointer;">&times;</button>

        <h3 class="mb-3">Registrar Nuevo Elemento</h3>

        <label for="tipoElementoSelect">Tipo de Elemento:</label>
        <select id="tipoElementoSelect" class="form-select mb-3" required>
            <option value="">Seleccione...</option>
            <option value="devolutivo">Devolutivo</option>
            <option value="consumible">Consumible</option>
        </select>

     <!-- Formulario Devolutivo -->
<form id="formDevolutivo" action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST" style="display:none;">
    <input type="hidden" name="elm_cod_tp_elemento" value="1">
    <input type="hidden" name="elm_existencia" value="1">
    <input type="hidden" name="elm_cod_estado" value="1">

    <div class="mb-3">
        <label for="elm_placa">Placa</label>
        <input type="number" name="elm_placa" id="elm_placa" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="elm_nombre">Nombre</label>
        <input type="text" placeholder="nombre Elemento" name="elm_nombre" id="elm_nombre" class="form-control" required>
    </div>

    <!-- Campo oculto para enviar Unidad de Medida = 1 siempre -->
    <input type="hidden" name="elm_uni_medida" value="1">

    <div class="mb-3">
        <label for="elm_area_cod">Área</label>
        <select name="elm_area_cod" id="elm_area_cod" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php foreach ($areas as $area): ?>
                <option value="<?= $area['codigo'] ?>"><?= htmlspecialchars($area['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Devolutivo</button>
</form>


        <!-- Formulario Consumible -->
        <form id="formConsumible" action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST" style="display:none;">
            <input type="hidden" name="elm_cod_tp_elemento" value="2">
            <input type="hidden" name="elm_cod_estado" value="1">

            <div class="mb-3">
                <label for="elm_placa_c">Placa</label>
                <input type="number" name="elm_placa" id="elm_placa_c" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="elm_nombre_c">Nombre</label>
                <input type="text" name="elm_nombre" id="elm_nombre_c" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="elm_existencia">Cantidad a Agregar</label>
                <input type="number" name="elm_existencia" id="elm_existencia" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label for="elm_uni_medida_c">Unidad de Medida</label>
                <select name="elm_uni_medida" id="elm_uni_medida_c" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Unidad</option>
                    <option value="2">Caja</option>
                    <option value="3">Paquete</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="elm_area_cod_c">Área</label>
                <select disabled id="elm_area_cod_c" class="form-select">
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['codigo'] ?>" <?= ($area['codigo'] == $area_general_codigo) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($area['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="elm_area_cod" value="<?= $area_general_codigo ?>">
            </div>

            <button type="submit" class="btn btn-primary">Guardar Consumible</button>
        </form>

    </div>
</div>




<!-- Modal Ver Más -->
<div id="modalVerMas">
    <div class="modal-content">
        <span class="close-btn" id="modalCerrar">&times;</span>
        <h4>Detalles del Elemento</h4>
        <table>
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
