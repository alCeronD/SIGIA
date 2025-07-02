<div class="container-fluid px-4">
    <div class="row valign-wrapper" style="margin-bottom: 20px;">
        <!-- Título -->
        <div class="col s12 m3">
            <h5 style="margin: 0;">Listado de Elementos</h5>
        </div>

        <!-- Botón de registro -->
        <div class="col s12 m3">
            <a id="abrirModalRegistrar" class="waves-effect waves-light btn">Registrar Nuevo Elemento</a>
        </div>

        <!-- El filtro -->
        <div class="col s12 m2">
            <div class="input-field" style="margin: 0;">
                <select id="filtroTipo">
                    <option value="todos" selected>Todos</option>
                    <option value="devolutivo">Devolutivo</option>
                    <option value="consumible">Consumible</option>
                </select>
            </div>
        </div>

        <!-- Input de búsqueda -->
        <div class="col s12 m2">
            <input id="inputBusqueda" type="text" placeholder="Buscar...">
        </div>

        <!-- Botón de buscar -->
        <div class="col s12 m2">
            <a id="btnBuscar" class="waves-effect waves-light btn">Buscar</a>
        </div>
    </div>

    <div class="table-responsive table-responsive-fixed">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
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
            <tbody id="tbodyElementos">
                <?php if (!empty($elementos)) : ?>
                    <?php foreach ($elementos as $elemento) : ?>
                        <tr data-tipo="<?= strtolower($elemento['tipoElemento']) ?>">

                            <td><?= htmlspecialchars($elemento['placa']); ?></td>
                            <td><?= htmlspecialchars($elemento['nombreElemento']); ?></td>
                            <td><?= htmlspecialchars($elemento['cantidad']); ?></td>
                            <td><?= htmlspecialchars($elemento['unidadMedida']); ?></td>
                            <td><?= htmlspecialchars($elemento['tipoElemento']); ?></td>
                            <td><?= htmlspecialchars($elemento['estadoElemento']); ?></td>
                            <td><?= htmlspecialchars($elemento['nombreArea']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm editar-btn"
                                        data-cod="<?= htmlspecialchars($elemento['codigoElemento']) ?>">
                                        <i class="material-icons">edit</i>
                                    </button>

                                    <?php 
                                    $tipoElemento = strtolower($elemento['tipoElemento']);

                                    if ($tipoElemento === 'consumible') : ?>
                                    <button type="button" id="btnAddCantidad" class="btn btn-danger btn-sm"><i class="material-icons">add</i></button>
                                    <?php endif; ?>

                                    <?php
                                    $estado = strtolower(trim($elemento['estadoElemento'] ?? ''));

                                    if ($estado == 'disponible') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Está seguro de inhabilitar este elemento?');"><i class="material-icons">backspace</i></a>

                                    <?php elseif ($estado == 'inhabilitado') : ?>
                                        <a href="<?= getUrl('elementos', 'elementos', 'cambiarEstadoElemento', ['elm_cod' => $elemento['codigoElemento']], 'dashboard') ?>"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirm('¿Está seguro de activar este elemento?');"><i class="material-icons">done_all</i></a>

                                    <?php else : ?>
                                        <span class="btn tooltipped" data-position="bottom" data-tooltip="I am a tooltip"><?= htmlspecialchars($estado) ?></span>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-info btn-sm btnVerMas" data-cod="<?= htmlspecialchars($elemento['codigoElemento']) ?>"><i class="material-icons">info_outline</i></button>
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

            <!-- FILA DE PAGINACION DENTRO DE LA TABLA -->
        </table>


    </div>
    <tfoot>
        <tr>
            <td colspan="9">
                <ul class="pagination center-align" style="margin-top: 20px;">
                    <!-- Botón Anterior -->
                    <li class="<?= ($pagina <= 1) ? 'disabled' : 'waves-effect' ?>">
                        <a href="dashboard.php?modulo=elementos&controlador=elementos&funcion=mostrarElementos&pagina=<?= max(1, $pagina - 1) ?>">
                            <i class="material-icons">chevron_left</i>
                        </a>
                    </li>

                    <!-- Números de página -->
                    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                        <li class="<?= ($pagina == $i) ? 'active teal lighten-1' : 'waves-effect' ?>">
                            <a href="dashboard.php?modulo=elementos&controlador=elementos&funcion=mostrarElementos&pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Botón Siguiente -->
                    <li class="<?= ($pagina >= $totalPaginas) ? 'disabled' : 'waves-effect' ?>">
                        <a href="dashboard.php?modulo=elementos&controlador=elementos&funcion=mostrarElementos&pagina=<?= min($totalPaginas, $pagina + 1) ?>">
                            <i class="material-icons">chevron_right</i>
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
    </tfoot>
</div>


<!-- El resto de tus modales y scripts quedan igual -->


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

            <div class="input-field">
                <input id="elm_placa" name="elm_placa" type="number" required>
                <label for="elm_placa">Placa</label>
            </div>

            <div class="input-field">
                <input id="elm_nombre" name="elm_nombre" type="text" required>
                <label for="elm_nombre">Nombre</label>
            </div>

            <div>
                <label for="elm_uni_medida">Unidad de Medida</label>
                <input id="elm_uni_medida" name="elm_uni_medida" type="text" value="1" readonly>
            </div>

            <div class="input-field">
                <select id="elm_area_cod" name="elm_area_cod" required>
                    <option value="" disabled selected>Seleccione...</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['codigo'] ?>"><?= htmlspecialchars($area['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="elm_area_cod">Área</label>
            </div>

            <button type="submit" class="btn waves-effect waves-light">Guardar Devolutivo</button>
        </form>
        <!-- Formulario Consumible -->
        <form id="formConsumible" action="<?= getUrl('elementos', 'elementos', 'registrarElemento', false, 'dashboard') ?>" method="POST" style="display:none;">
            <input type="hidden" name="elm_cod_tp_elemento" value="2">
            <input type="hidden" name="elm_cod_estado" value="1">

            <div class="input-field">
                <input id="elm_placa_c" name="elm_placa" type="number" required>
                <label for="elm_placa_c">Placa</label>
            </div>

            <div class="input-field">
                <input id="elm_nombre_c" name="elm_nombre" type="text" required>
                <label for="elm_nombre_c">Nombre</label>
            </div>

            <div class="input-field">
                <input id="elm_existencia" name="elm_existencia" type="number" min="1" required>
                <label for="elm_existencia">Cantidad a Agregar</label>
            </div>

            <div class="input-field">
                <select id="elm_uni_medida_c" name="elm_uni_medida" required>
                    <option value="" disabled selected>Unidad de medida</option>
                    <option value="1">Unidad</option>
                    <option value="2">Caja</option>
                    <option value="3">Paquete</option>
                </select>

            </div>

            <div class="input-field">
                <select id="elm_area_cod_c" disabled>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['codigo'] ?>" <?= ($area['codigo'] == $area_general_codigo) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($area['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="elm_area_cod" value="<?= $area_general_codigo ?>">
                <label for="elm_area_cod_c">Área</label>
            </div>

            <button type="submit" class="btn waves-effect waves-light">Guardar Consumible</button>
        </form>
    </div>
</div>

<!-- Modal Ver Más -->
<div id="modalVerMas" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div style="background:#fff; padding:20px; border-radius:8px; width:90%; max-width:600px; position:relative;">
        <span class="close-btn" id="modalCerrar" style="position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer;">&times;</span>
        <h4>Detalles del Elemento</h4>
        <table>
            <tbody>
                <tr>
                    <th>Código</th>
                    <td id="modalCod"></td>
                </tr>
                <tr>
                    <th>Placa</th>
                    <td id="modalPlaca"></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td id="modalNombre"></td>
                </tr>
                <tr>
                    <th>Existencia</th>
                    <td id="modalExistencia"></td>
                </tr>
                <tr>
                    <th>Unidad de Medida</th>
                    <td id="modalUniMedida"></td>
                </tr>
                <tr>
                    <th>Tipo de Elemento</th>
                    <td id="modalTipo"></td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td id="modalEstado"></td>
                </tr>
                <tr>
                    <th>Área</th>
                    <td id="modalArea"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Editar Elemento -->
<div id="modalEditarElemento" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:3000;">

    <button id="cerrarModalEditar" style="position:absolute; top:10px; right:15px; font-size:24px; background:none; border:none; cursor:pointer;">&times;</button>

    <form class="editar-form" action="<?= getUrl('elementos', 'elementos', 'editarElemento', false, 'dashboard') ?>" method="POST">
        <h2>Editar Elemento</h2>

        <input type="hidden" name="elm_cod" id="elm_cod" value="">
        <input type="hidden" name="elm_cod_tp_elemento" id="elm_cod_tp_elemento" value="">

        <div class="form-group">
            <label>Placa</label>
            <label id="label_placa"></label>
        </div>

        <div class="form-group">
            <label for="elm_nombre">Nombre</label>
            <input type="text" id="elm_nombre" name="elm_nombre" value="" required>
        </div>

        <div class="form-group">
            <label>Existencia</label>
            <label id="label_existencia"></label>
        </div>

        <div class="form-group">
            <label for="elm_uni_medida">Unidad de Medida</label>
            <input type="number" id="elm_uni_medida" name="elm_uni_medida" value="" required>
        </div>

        <div class="form-group">
            <label>Tipo de Elemento</label>
            <label id="label_tipoElemento"></label>
        </div>

        <div class="form-group">
            <label for="elm_area_cod">Área</label>
            <select id="elm_area_cod" name="elm_area_cod" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?= $area['codigo'] ?>"><?= htmlspecialchars($area['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" id="cancelarEditar" class="btn btn-secondary">Cancelar</button>
        </div>
    </form>

</div>


<script>
    window.elementosData = <?= json_encode($elementos) ?>;
</script>
<script type="module" src="../public/assets/js/elementos/elementos.js"></script>