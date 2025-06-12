<!-- Vista del tipo de documento -->
<div class="contentTpDocumento contentLayout">
    <div class="titleTp menuTitle">
        <span id="textTitleAreas">Tipos de documento</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formTpDocumento">
        <form id="formTp" class="formLayout">
            <div class="tpSiglaContent">
                <label for="tp_sigla">Sigla:</label>
                <input type="text" name="tp_sigla" id="tpSigla" placeholder="Sigla tipo documento" value="">
            </div>
            <div class="tpDescripcion">
                <label for="tp_nombre">Nombre:</label>
                <textarea name="tp_nombre" placeholder="Nombre tipodocumento" id="descripcionTp" value=""></textarea>
            </div>
            <div class="tpButton">
                <button type="submit" id="btnAreaUpdate">Registrar<button>
            </div>
        </form>
    </div>
    <div class="tblTpDocumento">
        <?php require_once 'tableViewtpDocumento.php'; ?>
    </div>
</div>


<!-- Modal -->
<div id="modalTp" class="modal">
    <!-- Modal content -->
    <div class="modal-content-tp">
        <span class="close">&times;</span>
        <span id="modalTitle">Actualizar registro</span>
        <form id="tpUpdateForm">
            <input type="text" name="tp_sigla" id="siglaTp_documento" placeholder="Sigla ...">
            <input type="text" name="tp_nombre" id="descripcionTp_documento" placeholder="Nombre..."></input>
            <button type="click" id="btnAreaUpdate">Agregar</button>
        </form>
    </div>

</div>

<script type="module" src="../public/assets/js/configModules/tpDocumento.js"></script>