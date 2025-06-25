<!-- Vista del tipo de documento -->
<div class="contentTpDocumento contentLayout">
    <div class="titleTp menuTitle">
        <span id="textTitleAreas" class="textTitleSpan">Tipos de documento</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formTpDocumento">
        <div class="card z-depth-2">
            <div class="card-content">
        <p class="flow-text card-title">Registrar Documento</p>
        <form id="formTp" class="formLayout">
            <div class="input-field tpSiglaContent">
                <input type="text" name="tp_sigla" id="tpSigla" value="">
                <label for="tpSigla">Sigla:</label>
            </div>
            <div class="input-field tpDescripcion">
                <textarea name="tp_nombre"  id="descripcionTp" class="materialize-textarea" value=""></textarea>
                <label for="tp_nombre">Nombre:</label>
            </div>
            <div class="tpButton">
                <button type="submit" class="btnSubmit waves-effect waves-light btn" id="btnAreaUpdate"><i class="material-icons">send</i><button>
            </div>
        </form>
        </div>
        </div>
    </div>
    <div class="tblTpDocumento highlight striped responsive-table">
        <?php require_once 'tableViewtpDocumento.php'; ?>
    </div>
</div>


<!-- Modal -->
<div id="modalTp" class="modal">
    <!-- Modal content -->
    <div class="modal-content-tp">
        <div class="titleSection">
            <span id="modalTitle">Tipo de documento</span>
            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <!-- <span class="close">&times;</span>
        <span id="modalTitle">Actualizar registro</span> -->
        <div class="formUpdateTp">
            <form id="tpUpdateForm">
            <div class="input-field contentTpSigla">
                <input type="text" name="tp_sigla" id="siglaTp_documento">
                <label for="tp_sigla">Sigla:</label>
            </div>
            <div class="input-field contentTpNombre">
                <textarea type="text" name="tp_nombre" class="materialize-textarea" id="descripcionTp_documento" ></textarea>
                <label for="tp_nombre">Nombre:</label>
            </div>
            <div class="contentTpBtn">
                <button type="click" id="btnTpUpdate" class="waves-effect waves-light btn"><i class="material-icons">save</i></button>
            </div>
        </form>
        </div>
        
    </div>

</div>

<script type="module" src="../public/assets/js/configModules/tpDocumento.js"></script>