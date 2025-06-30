<div id="modalDetail" class="modal" style="display: none;">
  <div class="modal-content " id="modalContentDetail">
    <div class="modal-title modalTitleDetail">
      <span id="modalTitle">Reserva #</span>
      <button type="button" id="closeModalBtn">
        <span class="close-modal">&times;</span>
      </button>
    </div>
    <div class="infoDetail">
      <form action="" id="formDetail">
        <div class="inputContent inputContentDetail nroIdentidad">
          <label for="nroIdentidad" class="labelForm labelFormDetail">Nro Identificación: <span class=" inputFormDetail" name="nroIdentidad" id="nroIdentidad"></span></label>
        </div>
        <div class="inputContent inputContentDetail nombreCompleto">
          <label for="nombreCompleto" class="labelForm labelFormDetail">Nombre: <span class="inputFormDetail" name="nombreCompleto" id="nombreCompleto"></span></label>
        </div>
        <div class="inputContent inputContentDetail fechaReserva">
          <label for="fechaReserva" class="labelForm labelFormDetail">Fecha reserva: <span class="inputFormDetail" name="fechaReserva" id="fechaReserva"></span></label>
        </div>
        <div class="inputContent inputContentDetail fechaSolicitud">
          <label for="fechaSolicitud" class="labelForm labelFormDetail">Fecha solicitud: <span class="inputFormDetail" name="fechaSolicitud" id="fechaSolicitud"></span></label>
        </div>
        <div class="inputContent inputContentDetail fechaDevolucion">
          <label for="fechaDevolucion" class="labelForm labelFormDetail">Fecha devolución: <span class="inputFormDetail" name="fechaDevolucion" id="fechaDevolucion"></span></label>
        </div>
        <div class="inputContent inputContentDetail observaciones">
          <label for="observaciones" class="labelForm labelFormDetail">Observaciones: <p class="inputFormDetail" id="observaciones" name="observaciones"></p></label>
        </div>
      </form>
    </div>

    <div class="tableContainer">
      <table>
        <thead>
          <tr>
            <th>Codigo </th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="BodydetailReserva">
          <!-- Datos se insertarán aquí -->
        </tbody>
      </table>
    </div>
  </div>
</div>