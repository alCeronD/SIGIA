<div class="contentMarca contentLayout">
    <div class="titleMarca menuTitle">
        <span id="textTitleAreas" class="textTitleSpan">Gestión de marcas</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formMarca">
        <div class="card z-depth-2">
            <div class="card-content">
            <p class="flow-text card-title">Registrar marca</p>
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
                <button type="submit" class="btnSubmit btnPermiso waves-effect waves-light btn" id="btnAreaUpdate" data-url="<?php echo getUrl('ConfigModules', 'configModules', 'addRow', false, 'dashboard'); ?>"><i class="material-icons">send</i><button></button>
            </div>
        </form>
        </div>
        </div>
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