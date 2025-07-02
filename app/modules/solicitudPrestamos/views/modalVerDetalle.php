<div id="modalDetalle" class="modal">
  <div class="modal-content">
    <div class="modal-title">
      <span id="modalTitle"></span>
      <button class="closeModalBtn btn-flat right" type="button">&times;</button>
    </div>

    <div id="contenidoDetalle" class="modal-container">
      <div class="itemsContent">
        <div class="rowDetails">
          <p class="titleDetail">Código del préstamo</p>
          <span id="detalle-pres_cod" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Fecha de solicitud</p>
          <span id="detalle-pres_fch_slcitud" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Fecha de reserva</p>
          <span id="detalle-pres_fch_reserva" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Hora de inicio</p>
          <span id="detalle-pres_hor_inicio" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Hora de fin</p>
          <span id="detalle-pres_hor_fin" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Fecha de entrega</p>
          <span id="detalle-pres_fch_entrega" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Observación</p>
          <span id="detalle-pres_observacion" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Destino del préstamo</p>
          <span id="detalle-pres_destino" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Estado</p>
          <span id="detalle-pres_estado_nombre" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Tipo de préstamo</p>
          <span id="detalle-tp_pres_nombre" class="valueDetail"></span>
        </div>

        <div class="rowDetails">
          <p class="titleDetail">Rol que solicitó</p>
          <span id="detalle-pres_rol_nombre" class="valueDetail"></span>
        </div>

        <!-- Tabla de elementos -->
        <div class="rowDetails">
          <!-- <h1 class="titleDetail">Préstamo</h1> -->
          <table id="tabla-elementos-prestamo" class="highlight centered">
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
</div>
