<!-- Sive para validar confirmación del un acción o no. -->
<div id="modalAddExistencia" class="modal">
    <div class="modalContentExistencia">
        <div class="modalContentTitleExistencia">
            <span id="titleModalExistencia">Agregar Existencia</span>
            <button type="button" class="closeModalBtn" id="cerrarModalExistencia">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <form id="formAddExistencia" class="addExistenciaForm">

            <input type="hidden" class="validate" name="elm_cod" id="codAddExistencia">

            <div class="input-field cantidad">
                <input type="number" name="co_cantidad" id="co_cantidad" class="validate" min="0">
                <label for="co_cantidad">Cantidad elemento * </label>
            </div>

            <div class="input-field tp_movimiento col s12">
                <span class="tooltipped" data-position="left" data-tooltip="Ingrese la cantidad disponible del elemento." id="infoTpMvnto"><i class="material-icons">help_outline</i></span>
                <select name="tipo_movimiento" id="tipo_movimiento">
                    <option value="" selected>Seleccione una opción</option>
                    <option value="1">Compra</option>
                    <option value="5">Reembolsar</option>
                </select>
                <label for="tipo_movimiento">Tipo de movimiento: * </label>
            </div>
            <div class="input-field co_descrip">
                <textarea name="descripcion_movimiento" id="descripcion_movimiento" class="materialize-textarea" maxlength="40" data-length="40"></textarea>
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