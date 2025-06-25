<div class="contentMarca contentLayout">
    <div class="titleMarca menuTitle">
        <span id="textTitleAreas">Marca</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formMarca">
        <form id="marcaForm" class="formLayout">
            <div class="input-field contentMarcaN">
                <input type="text" name="ma_nombre" id="ma_nombre" >
                <label for="ma_nombre">Nombre:</label>
            </div>
            <div class="input-field contentMarcaD">
                <textarea name="ma_descripcion" id="descripcionMarca" class="materialize-textarea"></textarea>
                <label for="descripcionMarca">Descripción:</label>
            </div>
            <div class="contentSubmitMa">
                <button type="submit" class="btnSubmit waves-effect waves-light btn" id="btnAreaUpdate"><i class="material-icons">send</i><button></button>
            </div>
        </form>
    </div>
    <div class="tblMarca highlight striped responsive-table">
        <?php require_once 'tableMarcaView.php'; ?>
    </div>

</div>


<!-- Modal -->
<div id="modalMarca" class="modal">
    <!-- Modal content -->
    <div class="modal-content-ma">
        <div class="titleSection">
            <span id="modalTitle">Actualizar registro</span>
            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="marcaUpdate">
            <form id="marcaUpdateForm">
                <div class="input-field nombreMaUpdte">
                    <input type="text" name="ma_nombre" id="nombreMarcaUpdate" >
                    <label for="ma_nombre">Nombre:</label>
                </div>
                <div class="input-field descripMaUpdte">
                    <textarea name="ma_descripcion" id="descripcionMarcaUpdate" ></textarea>
                    <label for="ma_descripcion">Descripción</label>
                </div>
                <div class="btnMaUpdate">
                    <button type="submit" id="btnMarcaUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
                </div>
            </form>
        </div>
    </div>

</div>

<script type="module" src="../public/assets/js/configModules/marcas.js"></script>