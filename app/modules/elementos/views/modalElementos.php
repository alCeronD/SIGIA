<!-- Modal Registrar Elemento -->
<div id="addElementModal" class="modal">
    <div id="modalContentElements">
        <div class="modalContentTitle">
            <span class="" id="titleModal">Nuevo Elemento</span>
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
                    <label for="elm_area_cod">Departamento * </label>
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