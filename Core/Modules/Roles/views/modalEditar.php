<!-- Modal de edición -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <div class="titleSection">
            <span>Editar rol</span>

            <button type="button" class="closeModalBtn" id="closeModalBtnEdit">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="formRol">
            <form action="<?php echo Router::createRoute(CR_ROLES, CR_ROLES, CR_EDITAR_ROL, false, CR_DASHBOARD_LOWER_CASE); ?>" method="POST" id="formEditarRol">
                <div>
                    <input type="hidden" name="rl_id" id="modal_rol_id">
                </div>
                <div class="input-field contentRlNombre">
                    <input type="text" name="rl_nombre" id="modal_rol_nombre">
                    <label for="rl_nombre">Rol:</label>
                </div>
                <div class="input-field">
                    <textarea name="rl_descripcion" id="modal_rol_descripcion" rows="3" class="materialize-textarea"></textarea>
                    <label for="rl_descripcion">Descripción:</label>
                </div>
                <div class="">
                    <button type="submit" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
                </div>
            </form>
        </div>

    </div>
</div>