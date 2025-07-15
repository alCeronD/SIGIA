<!-- Sive para validar confirmación del un acción o no. -->
<div id="modalAddExistencia" class="modal">
    <div class="modalContentExistencia">
        <div class="modalContentTitleExistencia">
            <span id="titleModalEditar">Agregar Existencia</span>
            <button type="button" class="closeModalBtn" id="cerrarModalEditar">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <form id="formAddExistencia" class="addExistenciaForm">

            <input type="hidden" class="validate" name="elm_cod">

            <div class="input-field cantidad">
                <input type="number" name="co_cantidad" id="co_cantidad" class="validate" min="0">
                <label for="co_cantidad">Cantidad elemento</label>
            </div>

            <div class="input-field tp_movimiento">
                <select name="tipo_movimiento" id="tipo_movimiento">
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="1">Compra</option>
                    <option value="5">Reembolsar</option>
                </select>
                <label for="tipo_movimiento">Tipo de movimiento</label>
            </div>

            <div class="input-field co_descrip">
                <textarea name="descripcion_movimiento" id="descripcion_movimiento" class="materialize-textarea"></textarea>
                <label for="descripcion_movimiento">Descripción</label>
            </div>

            <div class="modal-footer btnExistencia">
                <button class="waves-effect waves-light btn" id="btnAddExistencia" type="submit">
                    <i class="material-icons">send</i>
                </button>
            </div>

        </form>
    </div>
</div>

</div>