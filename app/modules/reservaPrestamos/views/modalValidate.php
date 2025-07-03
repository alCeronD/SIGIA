<div id="modalValidate" class="modal" style="display: none;">
  <div class="modal-content " id="modalContentValidate">
    <div class="modal-title modalTitleValidate">
      <span id="modalTitle">Reserva #</span>
      <button type="button" id="closeModalBtn">
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
                    <input id="allValidateItems" type="checkbox" value=""/>
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
    </div>
  </div>
</div>