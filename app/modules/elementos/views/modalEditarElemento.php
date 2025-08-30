<!-- Modal Editar Elemento -->
<div id="modalEditarElemento" class="">
    <div id="modalContentElements">
        <div class="modalContentTitleEditar">
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
                    <input id="elm_placa_editar" name="elm_placa" type="text" required>
                </div>
                <div class="serie">
                    <label for="">Serie:</label>
                    <input type="text" name="elm_serie" id="elm_serie_editar">
                </div>

                <div class="nombre">
                    <label for="elm_nombre_editar">Nombre elemento *</label>
                    <input id="elm_nombre_editar" name="elm_nombre" type="text" required>
                </div>
                <div class="area">
                    <label for="elm_area_cod_editar">Departamento *</label>
                    <select id="elm_area_cod_editar" name="elm_area_cod" required>
                    </select>
                </div>

                <div class="unidadMedida">
                    <label for="elm_ma_cod">Marca: </label>
                    <select class="" name="elm_ma_cod" id="elm_marca_cod_editar">
                        <option value="" selected>Seleccione una opción</option>
                    </select>
                </div>

                <div class="tipoElemento">
                    <label for="elm_cod_tp_elemento">Tipo De Elemento: *</label>
                    <select class="" name="elm_cod_tp_elemento" id="tp_elemento">
                        <option value="" selected>Seleccine una opción</option>
                        <option value="1">devolutivo</option>
                        <option value="2">consumible</option>
                    </select>
                </div>

                <div class="existencia">
                    <label for="elm_existencia">existencia</label>
                    <input id="elm_existencia_editar" name="elm_existencia" type="text" required disabled>
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