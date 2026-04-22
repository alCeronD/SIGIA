<div class="contentArea contentLayout">
    <div class="titleArea menuTitle">
        <span id="textTitleAreas" class="textTitleSpan">Departamentos</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formAr">
        <div class="card z-depth-2">
            <div class="card-content">
                <p class="flow-text card-title">Registrar departamento</p>
                <form id="formArea" class="formLayout">
                    <div class="input-field contentAreaNem">
                        <input type="text" name="ar_nombre" id="ar_nombre" class="validate">
                        <label for="ar_nombre">Nombre del departamento *</label>
                    </div>
                    <div class="input-field contentDescript">
                        <textarea name="ar_descripcion" id="ar_descripcion" class="materialize-textarea"></textarea>
                        <label for="ar_descripcion">Descripción del departamento</label>
                    </div>

                    <div class="contentSubmit">
                        <button type="submit" id="btnAreaSend" class=" waves-effect waves-light btn"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="tblAreas highlight striped responsive-table ">
        <!-- Tabla de vista. -->
        <?php require_once 'tableViewArea.php'; ?>

    </div>
</div>

<!-- Modal -->
<div id="modalArea" class="modal">
    <!-- Modal content -->
    <div class="modalContentArea modal-content">
        <div class="titleSection">
            <span id="modalTitle">Actualizar registro</span>
            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="formUpdate">
            <form id="areaUpdateForm" class="formLayout">
                <div class="input-field arNombreUpdate">
                    <label for="ar_nombre">Nombre:</label>
                    <input type="text" name="ar_nombre" id="nombreAreaUpdate">
                </div>
                <div class="input-field arDescripUpdate">
                    <textarea name="ar_descripcion" id="descripcionAreaUpdate" class="materialize-textarea"></textarea>
                    <label for="ar_descripcion">Descripción:</label>
                </div>
                <div class="arBtnUpdate">
                    <button type="submit" id="btnAreaUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- En la vista importar si o si el archivo específico a cada modulo -->
<script type="module" src="../public/assets/js/configModules/areas.js"></script>