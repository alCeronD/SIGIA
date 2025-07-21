<!-- Modal para validar los elementos y dar salida a los mismos. -->
<div id="modalValidate" class="modal" style="display: none;">
  <div class="modal-content " id="modalContentValidate">
    <div class="modal-title modalTitleValidate">
      <span id="modalTitle">Reserva #</span>
      <button type="button" id="closeModalBtnValidate">
        <span class="close-modal">&times;</span>
      </button>
    </div>
    <div class="infoDetail">
    </div>

    <div class="tableContainerDetail">
      <table>
        <thead>
          <tr>
            <th>Codigo </th>
            <th>Nombre</th>
            <th>Cantidad solicitada</th>
            <th>Tipo elemento</th>
            <th>
              Accion
              <div class="actions">

                <label>
                  <input id="allValidateItems" type="checkbox" value="" />
                  <span></span>
                </label>

              </div>

            </th>
          </tr>
        </thead>
        <tbody id="bodyDetailValidate">
          <!-- Datos se insertarán aquí -->
        </tbody>
      </table>

      <div class="formValidateContainer">
        <form id="formValidate">
          <div class="col s12">
            <label for="radioValidate">¿Deseas realizar una observación?</label>
            <div class="inputsRadio">
              <p>
                <label>
                  <input id="radioYes" class="with-gap" name="radioValidate" type="radio" />
                  <span>Si</span>
                </label>
              </p>
              <!-- validateNo -->
              <p>
                <label>
                  <input id="radioNo" class="with-gap" name="radioValidate" type="radio" />
                  <span>No</span>
                </label>
              </p>
            </div>
          </div>
          <div class="input-field col s12" id="textAreaObservacion">
            <textarea id="inputObservacion" name="textarea1" class="materialize-textarea" disabled></textarea>
            <label for="textarea1" >Observación: </label>
            <button type="submit" class="btn waves-effect waves-light" id="submitValidate"><i class="material-icons">send</i></button>
          </div>
        </form>
      </div>

      <div class="nextBtnValidate">
        <button class="btn waves-effect waves-light" id="previewBtnValidate">
          <i class="material-icons">arrow_back</i>
        </button>
        <button class="btn waves-effect waves-light" id="btnNextValidate">
          <i class="material-icons">navigate_next</i>
        </button>
      </div>
    </div>
  </div>
</div>