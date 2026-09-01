<div class="container">
  <div class="contentFunctions">
    <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
    <div class="titleModulos menuTitle">
      <!-- implementamos el texto con javascript. -->
      <span id="textTitleFunctions" class="textTitleSpan"></span>
      <button class="button waves-effect  btn" id="btnAddFunction"><i class="material-icons">add</i></button>
      <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
        class="close-btn"
        title="Volver al dashboard">&times;</a>
    </div>
    <div class="contentTableFunctios">
      <table id="tableFunctions" class="tableConfig tblConfigModules">
        <thead id="tblHeaderFunctions">
          <tr>
            <th id="idFuncion">Id de funcion</th>
            <th id="nombreFuncion">Nombre Función (function tecnica)</th>
            <th id="nombreFuncionLabel">Nombre Función (usuario final)</th>
            <th id="controladorAsociado">Controlador asociado</th>
            <th id="tipoDeFuncion">Tipo de función (visual o logica)</th>
            <th id="">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyFunctions">
        </tbody>
        <!-- se renderiza la paginacion -->
        <tfoot id="tFooterFunciones"></tfoot>
      </table>
    </div>
  </div>

  <!-- modal createFunction -->
  <div id="modalAddFunction" class="modal modal-overlay">
    <!-- Modal content -->
    <div class="modalContentFunctions modal-content .modal-closebtn">
      <div class="titleSection">
        <span id="modalTitle">Registrar Función</span>
        <button type="button" class="closeModalBtn" id="closeModalBtnInsert">
          <span class="close-modal">&times;</span>
        </button>
      </div>
      <div class="formInsert">
        <form id="formInsertFunction" class="formLayout" action="<?php echo Router::createRoute(CR_FUNCIONES, 'Funciones', 'store', false, CR_DASHBOARD_LOWER_CASE); ?>">
          <div class="input-field mNombreUpdate">
            <label for="nombre_funcion">Nombre funcion técnica: *</label>
            <input type="text" name="nombre_funcion" id="nombre_funcion">
          </div>
          <div class="input-fiel mIcono">
            <label for="nombre_funcion_user">Nombre de funcion final: *</label>
            <input type="text" name="nombre_funcion_user" id="nombre_funcion_user">
          </div>
          <div class="input-field tipoFuncion">
            <span>Tipo de funcion: *</span>
            <p>
              <label>
                <input class="with-gap" name="tp_funcion" type="radio" id="tp_funcion_logica" value="logica" />
                <span>Logica</span>
              </label>
              <label>
                <input class="with-gap" name="tp_funcion" type="radio" id="tp_funcion_render" value="render" />
                <span>Render</span>
              </label>
            </p>
          </div>
          <div class="input-fiel files">
            <!-- selector en donde van los los selects, se renderiza por javascript en la funcion RENDER SELECTS. -->

          </div>

          <div class="mBtnUpdate">
            <button type="submit" id="btnAreaUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="modalEditFunction" class="modal modal-overlay">
    <!-- Modal content -->
    <div class="modalContentFunctions modal-content .modal-closebtn">
      <div class="titleSection">
        <span id="modalTitle">Editar función</span>
        <button type="button" class="closeModalBtn" id="closeModalBtnEdit">
          <span class="close-modal">&times;</span>
        </button>
      </div>
      <div class="formUpdate">
        <form id="formUpdateFunction" class="formLayout" action="<?php echo Router::createRoute(CR_FUNCIONES, 'FuncionesModulo', 'save', false, CR_DASHBOARD_LOWER_CASE); ?>">
          <input type="hidden" name="id_funcion" id="id_funcion">
          <div class="input-field mNombreUpdate">
            <label for="nombre_funcion">Nombre funcion técnica: *</label>
            <input type="text" name="nombre_funcion" id="nombre_funcion">
          </div>
          <div class="input-fiel mIcono">
            <label for="nombre_funcion_user">Nombre de funcion final: *</label>
            <input type="text" name="nombre_funcion_user" id="nombre_funcion_user">
          </div>
          <div class="input-field tipoFuncion">
            <span>Tipo de funcion: *</span>
            <p>
              <label>
                <input class="with-gap" name="tp_funcion" type="radio" id="tp_funcion_logica" value="logica" />
                <span>Logica</span>
              </label>
              <label>
                <input class="with-gap" name="tp_funcion" type="radio" id="tp_funcion_render" value="render" />
                <span>Render</span>
              </label>
            </p>
          </div>
          <div class="input-fiel files">
            <!-- selector en donde van los los selects, se renderiza por javascript en la funcion RENDER SELECTS. -->

          </div>

          <div class="mBtnUpdate">
            <button type="submit" id="btnAreaUpdate" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>