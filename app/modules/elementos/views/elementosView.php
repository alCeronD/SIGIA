<div class="container-fluid px-4">
    <div class="row valign-wrapper" style="margin-bottom: 20px;">
        <!-- Título -->
        <div class="col s12 m7">
            <h5 style="margin: 0;">Gestión de elementos</h5>
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
            <span class="" id="titleModal">Registrar Nuevo Elemento</span>
            <button type="button" class="closeModalBtn" id="cerrarModalRegistrar">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="modalContentForm">
            <form id="addElementForm">
                <div class="placa">
                    <!-- Inputs radio de la placa, dependiendo de la placa, me debe de mostrar uno u otro. -->
                    <div class="radioPlaca">
                        <label for="">¿Desea asociar el elemento nuevo a una placa o registrar una placa nueva? *</label>
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
                                <label for="elm_serie">Código de serie  Ejemplo = 922919587-1</label>
                            </div>
                        </div>
                        <!-- INPUTS DE LAS PLACAS ASOCIADAS. -->
                        <div class="placaAssocContent">
                            <div class="selectPlaca">
                                <label for="elm_placa">Digite el número de placa</label>
                                <span id="respuestaPlaca" style="display: none;"></span>
                                <input type="text" name="elm_placa" id="searchPlaca">
                            </div>
                            <div class="contentPlacaAssoc ">
                                <label for="serialPlaca">Serial asociado</label>
                                <input type="text" name="elm_serie" id="serialPlacaAssoc">
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
                    <label for="elm_nombre">Nombre elemento: *</label>
                    <input id="elm_nombre" name="elm_nombre" type="text" placeholder="">
                </div>
                <div class="area">
                    <label for="elm_area_cod">Área * </label>
                    <select id="selectAreas" class="select_area" name="elm_area_cod">
                    </select>
                </div>

                <div class="categoria">
                    <label for="categoriaSelect">Categorias:</label>
                    <select name="categoriaSelect" id="selectCategorias" class=""></select>
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
                                    <input class="with-gap" name="elm_cod_tp_elemento" type="radio" id="devolutivoCheckbox" value="1" />
                                    <span>Devolutivo</span>
                                </label>
                            </p>
                        </div>
                        <div class="checkboxConsumible">
                            <p>
                                <label>
                                    <input class="with-gap" name="elm_cod_tp_elemento" type="radio" id="consumibleCheckbox" value="2" />
                                    <span>Consumible</span>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="checkboxTpElemento">
                        <div class="unidadMedida">
                            <select class="" name="elm_uni_medida" id="undMedida">
                                <option value="" selected>Seleccione una opción</option>
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

                <div class="apuntes">
                    <!-- Observacion -->
                    <div class="input-field observacion">
                        <textarea id="observacionInput" class="materialize-textarea" name="elm_observacion" data-length="120"></textarea>
                        <label for="observacionInput">Observación</label>
                    </div>
                    <div class="input-field sugerencia">
                        <textarea id="sugerenciaInput" class="materialize-textarea" name="elm_sugerencia" data-length="120"></textarea>
                        <label for="sugerenciaInput">Sugerencia</label>
                    </div>
                </div>

                <div class="modal-footer footerBtn">
                    <button type="submit" class="btn waves-effect waves-light left"><i class="material-icons">save</i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Elemento -->

<div id="modalEditarElemento" class="">
    <div id="modalContentElements">
        <div class="modalContentTitle">
            <span id="titleModalEditar">Editar Elemento</span>
            <button type="button" class="closeModalBtn" id="cerrarModalEditar">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="modalContentForm">
            <form id="editarElementForm">
                <div class="codElemento">
                    <input type="hidden" name="elm_cod" id="codElementoEditar" value="">
                </div>
                <div class="placa">
                    <label>Placa:</label>
                    <label id="label_placa"></label>
                    <input id="elm_placa_editar" name="elm_nombre" type="text" required>
                </div>
                <div class="placaInputsEditar">
                    <!-- INPUTS DE PLACAS QUE SELECCIONE EL INPUT RADIO NUEVA PLACA -->
                    <div class="contentPlacaEdit input-field">
                        <!-- <div class="inputPlacaEditar">
                            <input id="elm_placa" name="elm_placa" type="text">
                            <label for="elm_placa">Número de placa *</label>
                        </div> -->
                        <div class="inputSerieEdit input-field">
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
                            <input type="text" name="searchPlaca" id="searchPlaca">
                        </div>
                        <div class="contentPlacaAssoc ">
                            <label for="serialPlaca">Serial asociado</label>
                            <input type="text" name="serialPlaca" id="serialPlacaAssoc">
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

                <div class="nombre">
                    <label for="elm_nombre_editar">Nombre elemento *</label>
                    <input id="elm_nombre_editar" name="elm_nombre" type="text" required>
                </div>
                <div class="area">
                    <label for="elm_area_cod_editar">Área *</label>
                    <select id="elm_area_cod_editar" name="elm_area_cod" required>
                    </select>
                </div>

                <div class="unidadMedida">
                    <select class="" name="elm_uni_medida_select" id="undMedida">
                        <option value="0" selected>Seleccione una opción</option>
                        <option value="1">Unitario</option>
                        <option value="2">Caja</option>
                        <option value="3">Galon</option>
                    </select>
                </div>

                <div class="tipoElemento">
                    <label for="tp_elemento">Tipo de Elemento:</label>
                    <select class="" name="tp_elemento" id="tp_elemento">
                        <option value="0" selected>Seleccione una opción</option>
                        <option value="1">devolutivo</option>
                        <option value="2">consumible</option>
                    </select>
                </div>

                <div class="existencia">
                    <label for="elm_existencia">existencia</label>
                    <input id="elm_existencia_editar" name="elm_existencia" type="text" required>
                </div>
                <div class="apuntes">
                    <div class="input-field observacion">
                        <label for="observacionInputEditar">Observación</label>
                        <textarea id="observacionInputEditar" class="materialize-textarea" placeholder="Observacion" name="elm_observacion" data-length="120"></textarea>
                    </div>
                    <div class="input-field sugerencia">
                        <label for="sugerenciaInputEditar">Sugerencia</label>
                        <textarea id="sugerenciaInputEditar" class="materialize-textarea" placeholder="Sugerencia " name="elm_sugerencia" data-length="120"></textarea>
                    </div>
                </div>
                <div class="modal-footer footerBtn">
                    <button type="submit" class="btn waves-effect waves-light left">
                        <i class="material-icons">save</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Más -->
<div id="modalVerMas" class="modal">
    <div>
        <button id="modalCerrarVerMas">
            <span class="close-btn" id="" style="position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer;">&times;</span>
        </button>
        <h4>Detalles del Elemento</h4>
        <table>
            <tbody>
                <tr>
                    <th>Código</th>
                    <td id="modalPlaca"></td>
                </tr>
                <tr>
                    <th>Placa</th>
                    <td id="modalSerie"></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td id="modalNombreElemento"></td>
                </tr>
                <tr>
                    <th>Existencia</th>
                    <td id="modalCantidad"></td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <th>Tipo de Elemento</th>
                    <td id="modalTipo"></td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td id="modalEstadoElemento"></td>
                </tr>
                <tr>
                    <th>Área</th>
                    <td id="modalArea"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal confirmación -->
<!-- Sive para validar confirmación del un acción o no. -->
<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <h5 id="modalConfirmacionTitulo">Confirmación</h5>
        <p id="modalConfirmacionMensaje">¿Estás seguro de realizar esta acción?</p>
    </div>
    <!-- el ! significa un elemento de referencia hacia javascript. -->
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat" id="btnCancelar">Cancelar</a>
        <a href="#!" class="modal-close waves-effect waves-red btn" id="btnAceptar">Aceptar</a>
    </div>
</div>

<script type="module" src="../public/assets/js/elementos/elementosNew.js"></script>