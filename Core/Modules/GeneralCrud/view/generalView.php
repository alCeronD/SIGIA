<div class="contentGeneral contentLayout">
  <div class="titleArea menuTitle">
        <span id="textTitleAreas" class="textTitleSpan"><?PHP ECHO GC_TITLE_DEPARTAMENTO; ?></span>
        <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
  </div>
    <div class="formAr">
        <div class="card z-depth-2">
            <div class="card-content">
                <p class="flow-text card-title"><?PHP ECHO GC_TITLE_REGISTRAR; ?></p>
                <form id="formGeneral" class="formLayout" action="<?php echo Router::createRoute('GeneralCrud','GeneralCrud', 'insert',false, 'dashboard'); ?>">
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
          <tbody id="tableBodyArea">
              <!-- Renderizado con javascript. -->
          </tbody>
        </table>
    </div>

</div>

<script type="module" src="../public/assets/js/GeneralCrud/GeneralCrud.js"></script>