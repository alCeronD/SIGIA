<div id="modalDetalle" class="modal">
  <div class="modal-content">
    <div class="row">
      <div class="col s12">
        <h5 id="modalTitle" class="teal-text text-darken-3"></h5>
        <a class="btn-flat right closeModalBtn red" type="button" title="Cerrar">&times;</a>
      </div>
    </div>

    <div id="contenidoDetalle" class="row">
      <div class="col s12 m6">
        <p><strong>Código del préstamo:</strong> <span id="detalle-pres_cod"></span></p>
        <p><strong>Fecha de solicitud:</strong> <span id="detalle-pres_fch_slcitud"></span></p>
        <p><strong>Fecha de reserva:</strong> <span id="detalle-pres_fch_reserva"></span></p>
        <!-- <p><strong>Hora de inicio:</strong> <span id="detalle-pres_hor_inicio"></span></p>
        <p><strong>Hora de fin:</strong> <span id="detalle-pres_hor_fin"></span></p> -->
        <p><strong>Fecha de entrega:</strong> <span id="detalle-pres_fch_entrega"></span></p>
      </div>

      <div class="col s12 m6">
        <p><strong>Observación:</strong> <span id="detalle-pres_observacion"></span></p>
        <p><strong>Destino del préstamo:</strong> <span id="detalle-pres_destino"></span></p>
        <p><strong>Estado:</strong> <span id="detalle-pres_estado_nombre"></span></p>
        <p><strong>Tipo de préstamo:</strong> <span id="detalle-tp_pres_nombre"></span></p>
        <p><strong>Rol que solicitó:</strong> <span id="detalle-pres_rol_nombre"></span></p>
      </div>
    </div>

    <div class="row">
      <div class="col s12">
        <h6 class="teal-text text-darken-2">Elementos del préstamo</h6>
        <table id="tabla-elementos-prestamo" class="highlight responsive-table centered">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Placa</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
