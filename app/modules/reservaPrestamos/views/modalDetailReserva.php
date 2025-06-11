<div id="modalDetail" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-title">
      <span id="modalTitle">Reserva #</span>
      <button type="button" id="closeModalBtn">
        <span class="close-modal">&times;</span>
      </button>
    </div>

    <div id="infoDetail">
      <form action="" id="formDetail">
        <div class="inputContent inputContentDetail nroIdentidad">
          <label for="nroIdentidad" class="labelForm labelFormDetail">Nro Identificación:</label>
          <input type="number" class="inputForm inputFormDetail" name="nroIdentidad" id="nroIdentidad" placeholder="Identificación...">
        </div>

        <div class="inputContent inputContentDetail nombreCompleto">
          <label for="nombreCompleto" class="labelForm labelFormDetail">Nombre:</label>
          <input type="text" class="inputForm inputFormDetail" name="nombreCompleto" id="nombreCompleto">
        </div>

        <div class="inputContent inputContentDetail fechaReserva">
          <label for="fechaReserva" class="labelForm labelFormDetail">Fecha reserva:</label>
          <input type="date" class="inputForm inputFormDetail" name="fechaReserva" id="fechaReserva">
        </div>

        <div class="inputContent inputContentDetail fechaSolicitud">
          <label for="fechaSolicitud" class="labelForm labelFormDetail">Fecha solicitud:</label>
          <input type="date" class="inputForm inputFormDetail" name="fechaSolicitud" id="fechaSolicitud">
        </div>

        <div class="inputContent inputContentDetail fechaDevolucion">
          <label for="fechaDevolucion" class="labelForm labelFormDetail">Fecha devolución:</label>
          <input type="date" class="inputForm inputFormDetail" name="fechaDevolucion" id="fechaDevolucion">
        </div>

        <div class="inputContent inputContentDetail observaciones">
          <label for="observaciones" class="labelForm labelFormDetail">Observaciones:</label>
          <textarea name="observaciones" class="inputForm inputFormDetail" id="observaciones"></textarea>
        </div>
      </form>
    </div>

    <div class="tableContainer">
      <table>
        <thead>
          <tr>
            <th>Item #1</th>
            <th>Item #2</th>
            <th>Item #3</th>
            <th>Item #4</th>
          </tr>
        </thead>
        <tbody id="BodydetailReserva">
          <!-- Datos se insertarán aquí -->
        </tbody>
      </table>
    </div>
  </div>
</div>