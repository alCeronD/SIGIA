<div class="contentMarca contentLayout">
    <div class="titleMarca menuTitle">
        <span id="textTitleAreas">Marca</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formMarca">
        <form id="marcaForm" class="formLayout">
            <div class="contentMarcaN">
                <label for="ma_nombre">Nombre:</label>
                <input type="text" name="ma_nombre" id="nombreMarca" placeholder="Nombre marca">
            </div>
            <div class="contentMarcaD">
                <label for="ma_descripcion">Descripción:</label>
                <textarea name="ma_descripcion" id="descripcionMarca" placeholder="Descripción"></textarea>
            </div>
            <div class="contentSubmitMa">
                <button type="submit" id="btnMarca">Guardar</button>
            </div>
        </form>
    </div>
    <div class="tblMarca">
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
                <div class="nombreMaUpdte">
                    <label for="ma_nombre">Nombre:</label>
                    <input type="text" name="ma_nombre" id="nombreMarcaUpdate" placeholder="Nombre area...">
                </div>
                <div class="descripMaUpdte">
                    <label for="ma_descripcion">Descripción</label>
                    <textarea name="ma_descripcion" id="descripcionMarcaUpdate" placeholder="Descripción..."></textarea>
                </div>
                <div class="btnMaUpdate">
                    <button type="click" id="btnMarcaUpdate">Agregar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script type="module" src="../public/assets/js/configModules/marcas.js"></script>