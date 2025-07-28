<!-- Modal de edición -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <div class="titleSection">
            <span>Editar rol</span>

            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="formRol">
            <form id="formEditarRol">
            <div>
                <input type="hidden" name="rol_id" id="modal_rol_id">
            </div>
            <div class="input-field contentRlNombre">
                <input type="text" name="modal_rol_nombre" id="modal_rol_nombre" >
                <label for="modal_rol_nombre">Rol:</label>
            </div>

            <div class="input-field"> 
                <textarea name="rol_descripcion" id="modal_rol_descripcion" rows="3" class="materialize-textarea" ></textarea>
                <label for="modal_rol_descripcion">Descripción:</label>
            </div>
            <div class="">
                <button type="submit" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
            </div>
            </form>
        </div>
        
    </div>
</div>