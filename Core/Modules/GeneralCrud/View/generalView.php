<div class="contentGeneral contentLayout">

    <div class="titleArea menuTitle">
        <span id="textTitleAreas" class="textTitleSpan"><?PHP echo GC_TITLE_DEPARTAMENTO; ?></span>
        <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="formAr">
        <div class="card z-depth-2">
            <div class="card-content">
                <p class="flow-text card-title"><?PHP echo GC_TITLE_REGISTRAR; ?></p>
                <form id="formGeneral" class="formLayout" action="<?php echo Router::createRoute('GeneralCrud', 'GeneralCrud', 'insert', false, 'dashboard'); ?>">
                    <div class="input-field contentAreaNem">
                        <input type="text" name="gc_nombre" id="ar_nombre" class="validate">
                        <label for="ar_nombre"><?PHP echo GC_WORD_NOMBRE_ITEM; ?></label>
                    </div>
                    <div class="input-field contentDescript">
                        <textarea name="gc_descrip" id="ar_descripcion" class="materialize-textarea"></textarea>
                        <label for="gc_descrip"><?PHP echo GC_WORD_DESCRIPT_ITEM; ?></label>
                    </div>

                    <div class="contentSubmit">
                        <button type="submit" id="btnGeneralSend" class=" waves-effect waves-light btn"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="tblGeneral highlight striped responsive-table ">
        <table class="tableConfig tblConfigModules z-depth-2" id="tableGeneral">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estatus</th>
                    <th>Opción</th>
                </tr>
            </thead>
            <tbody id="tblBodyGeneralCrud">
            </tbody>
            <tfoot id="tblFooterGeneralCrud"></tfoot>

        </table>
    </div>

    <!-- MODAL -->
    <div id="modalGeneralCrud" class="modal">
        <!-- Modal content -->
        <div class="modal-content-ma">
            <div class="titleSection">
                <span id="modalTitle">Actualizar registro</span>
                <button type="button" class="closeModalBtn">
                    <span class="close-modal">&times;</span>
                </button>
            </div>
            <div class="marcaUpdate">
                <form id="generalCrudUpdate" action="<?php echo Router::createRoute('GeneralCrud', 'GeneralCrud', 'update', false, 'dashboard') ?>">
                    <div class="input-field idMaUpdate">
                        <input type="hidden" name="gc_id" id="idGeneralCrudUpdate">
                    </div>
                    <div class="input-field nombreMaUpdte">
                        <input type="text" name="gc_nombre" id="nombreGeneralCrudUpdate">
                        <label for="gc_nombre">Nombre:</label>
                    </div>
                    <div class="input-field descripMaUpdte">
                        <textarea name="gc_descrip" id="descripcionGeneralCrudUpdate"></textarea>
                        <label for="gc_descrip">Description:</label>
                    </div>
                    <div class="btnMaUpdate">
                        <button type="submit" id="btnGeneralCrudUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

<?php
foreach ($_SESSION['js'] as $key => $url) { ?>
    <script type="module" src="<?php echo htmlspecialchars("/../../Core/" . $url); ?>"></script>
<?php } ?>