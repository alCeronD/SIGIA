<div class="container">
    <div class="contentArea">
        <div class="headerContent">
            <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
            <div class="titleArea menuTitle">
                <span id="textTitleAreas" class="textTitleSpan"><?php echo AR_DEPARTAMENTO; ?></span>
                <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
                    class="close-btn"
                    title="Volver al dashboard">&times;</a>
            </div>
        </div>
        <div class="formAr">
            <p class="flow-text card-title"><?php echo AR_ADD_DEPARTAMENTO; ?></p>
            <form id="formArea" class="formLayout">
                <div class="input-field contentAreaNem">
                    <input type="text" name="ar_nombre" id="ar_nombre" class="validate">
                    <label for="ar_nombre"><?php echo AR_NOMBRE_DEPA; ?></label>
                </div>
                <div class="input-field contentDescript">
                    <textarea name="ar_descripcion" id="ar_descripcion" class="materialize-textarea"></textarea>
                    <label for="ar_descripcion"><?php echo AR_DESC_DEPA; ?></label>
                </div>
                <div class="contentSubmit">
                    <button type="submit" id="btnAreaSend" class=" waves-effect waves-light btn"></button>
                </div>
            </form>
        </div>
        <div class="tblAreas highlight striped responsive-table ">
            <?php require_once 'tableViewArea.php'; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalArea" class="modal modal-overlay">
        <!-- Modal content -->
        <div class="modalContentArea modal-content .modal-closebtn">
            <div class="titleSection">
                <span id="modalTitle">Actualizar registro</span>
                <button type="button" class="closeModalBtn">
                    <span class="close-modal">&times;</span>
                </button>
            </div>
            <div class="formUpdate">
                <form id="areaUpdateForm" class="formLayout">
                    <input type="hidden" name="ar_cod" id="idCodigo">
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
</div>