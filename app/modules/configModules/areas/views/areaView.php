<!-- TODO: Mover al archivo específico de la vista de area. -->
<div class="contentArea contentLayout">
    <div class="titleArea menuTitle">
        <span id="textTitleAreas">Areas</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formAr">
        <form id="formArea" class="formLayout">
            <div class="contentAreaNem">
                <label for="ar_nombre">Area</label>
                <input type="text" name="ar_nombre" id="nombreArea" placeholder="Nombre area..." value="">
            </div>
            <div class="contentDescript">
                <label for="ar_descripcion">Descripción</label>
                <textarea name="ar_descripcion" placeholder="Descripción general del area" id="descripcionArea" value=""></textarea>
            </div>
            <div class="contentSubmit">
                <button type="submit" id="btnAreaUpdate" class="btnSubmit">Registrar</button>
            </div>
        </form>
    </div>

    <div class="tblAreas">
        <!-- Tabla de vista. -->
        <?php require_once 'tableViewArea.php'; ?>
    </div>
</div>



<!-- Modal -->
<div id="modalArea" class="modal">
    <!-- Modal content -->
    <div class="modalContentArea">
        <div class="titleSection">
            <span id="modalTitle">Actualizar registro</span>
            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="formUpdate">
            <form id="areaUpdateForm" class="formLayout">
                <div class="arNombreUpdate">
                    <label for="ar_nombre">Nombre:</label>
                    <input type="text" name="ar_nombre" id="nombreAreaUpdate" placeholder="Nombre area...">
                </div>
                <div class="arDescripUpdate">
                    <label for="ar_descripcion">Descripción:</label>
                    <textarea name="ar_descripcion" id="descripcionAreaUpdate" placeholder="Descripción..."></textarea>
                </div>
                <div class="arBtnUpdate">

                    <button type="click" id="btnAreaUpdate">Actualizar</button>
                </div>
            </form>
        </div>


    </div>
</div>

<!-- En la vista importar si o si el archivo específico a cada modulo -->
<script type="module" src="../public/assets/js/configModules/areas.js"></script>