<div class="container-fluid px-4">
    <div class="row valign-wrapper" style="margin-bottom: 20px;">
        <!-- Título -->
        <div class="col s12 m6">
            <h5 style="margin: 0;">Listado de Elementos</h5>
        </div>

        <!-- Botón de registro -->
        <div class="col s12 m6 center">
            <button type="button" class="waves-effect waves-light btn" id="btnAddModalElements"></button>
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
            <tbody id="tbodyElements">

            </tbody>

            <!-- FILA DE PAGINACION DENTRO DE LA TABLA -->
        </table>


    </div>
    <tfoot>
        <tr>
            <td colspan="9">
                <ul class="pagination center-align" style="margin-top: 20px;">
                    <!-- Botón Anterior -->
                    <li class="waves-effect">

                        <button type="button" class="waves-effect btn" id="previewElements">
                            <i class="material-icons">chevron_left</i>

                        </button>
                    </li>
                    <!-- Botón Siguiente -->
                    <li class="waves-effect">
                        <button type="button" class="waves-effect btn" id="nextElements">
                            <i class="material-icons">chevron_right</i>
                        </button>
                    </li>
                </ul>
            </td>
        </tr>
    </tfoot>
</div>

<!-- Modal Registrar Elemento -->
<div id="addElementModal" class="">
    <div id="modalContentElements">
        <div class="modalContentTitle">
            <span class="">Registrar Nuevo Elemento</span>
            <button type="button" class="closeModalBtn" id="cerrarModalRegistrar">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="modalContentForm">
            <form id="addElementForm">
                <div class="placa">
                    <!-- Inputs radio de la placa, dependiendo de la placa, me debe de mostrar uno u otro. -->
                    <div class="radioPlaca">
                        <label for="">¿Desea asociar el elemento nuevo a una placa o registrar una placa nueva?</label>
                        <div class="newPlaca">
                            <p>
                                <label>
                                    <input class="with-gap" name="placaRadio" type="radio" id="nuevaPlaca" />
                                    <span>Nueva placa</span>
                                </label>
                            </p>
                        </div>
                        <div class="selectedPlaca">
                            <p>
                                <label>
                                    <input class="with-gap" name="placaRadio" type="radio" id="selectPlaca" />
                                    <span>Asociar placa</span>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="placaInputs">
                        <!-- INPUTS DE PLACAS QUE SELECCIONE EL INPUT RADIO NUEVA PLACA -->
                        <div class="contentPlaca input-field">
                            <div class="inputPlaca">
                                <input id="elm_placa" name="elm_placa" type="text">
                                <label for="elm_placa">Número de placa *</label>
                            </div>
                            <div class="inputSerie input-field">
                                <!-- Validar, no se deben permitir catacteres con el arroba o el # -->
                                <input id="elm_serie" name="elm_serie" type="text">
                                <label for="elm_serie">Código de serie * Ejemplo = 922919587-1</label>
                            </div>
                        </div>
                        <!-- INPUTS DE LAS PLACAS ASOCIADAS. -->
                        <div class="placaAssocContent">
                            <div class="selectPlaca">
                                <label for="searchPlaca">Digite el número de placa</label>
                                <span id="respuestaPlaca" style="display: none;"></span>
                                <input type="text"  name="searchPlaca" id="searchPlaca">
                            </div>
                            <div class="contentPlacaAssoc ">
                                <label for="serialPlaca">Serial asociado</label>
                                <input type="text" name="serialPlaca" id="serialPlacaAssoc" >
                            </div>
                            <div class="tableResult">
                                <table class="striped responsive-table" id="tablePlaca">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Serial registrado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPlacaResult"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="nombre">
                    <label for="elm_nombre">Nombre elemento:</label>
                    <input id="elm_nombre" name="elm_nombre" type="text" placeholder="">
                </div>
                <div class="area">
                    <label for="areaCod">Área * </label>
                    <select id="selectAreas" class="select_area" name="areaCod">
                    </select>
                </div>

                <div class="categoria">
                    <label for="categoriaSelect">Categorias:</label>
                    <select name="categoriaSelect" id="categoriaSelect" class=""></select>
                </div>

                <div class="marca">
                    <label for="selectMarca">Marcas:</label>
                    <select class="" name="selectMarca" id="selectMarca"></select>
                </div>

                <div class="tipoElemento">
                    <div class="radioTpElemento">
                        <label for="selectTpElemento">Tipo Elemento:</label>
                        <div class="checkboxDevolutivo">
                            <p>
                                <label>
                                    <input class="with-gap" name="tpElemento" type="radio" id="devolutivoCheckbox" value="1"/>
                                    <span>Devolutivo</span>
                                </label>
                            </p>
                        </div>
                        <div class="checkboxConsumible">
                            <p>
                                <label>
                                    <input class="with-gap" name="tpElemento" type="radio" id="consumibleCheckbox" value="2"/>
                                    <span>Consumible</span>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="checkboxTpElemento">
                        <div class="unidadMedida">
                            <select class="" name="elm_uni_medida" id="undMedida">
                                <option value="0" selected>Seleccione una opción</option>
                                <option value="1">Unitario</option>
                                <option value="2">Caja</option>
                                <option value="3">Galon</option>
                            </select>
                            <label for="elm_uni_medida">Unidad Medida:</label>
                        </div>
                        <div class="cantidadElemento">
                            <input type="text" name="elm_existencia" id="inputCantidad">
                            <label for="elm_existencia">Cantidad:</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"  class="btn waves-effect waves-light left"><i class="material-icons">save</i></button>
                </div>


            </form>
        </div>
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


<script type="module" src="../public/assets/js/elementos/elementosNew.js"></script>