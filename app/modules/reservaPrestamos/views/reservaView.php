<!-- la función getUrl se puede acceder porque la tenemos incluida directamente en el dashboard. -->

<div class="content">
    <div class="menuTitle">
        <span id="textTitle">Reserva</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="searchUser">
        <label for="">Instructor: </label>
        <button type="button" id="searchBtn" class="btnClick"></button>
    </div>
    <div class="solicPrestamos">
        <form id="formSolicitudPrestamo">
            <div class="inputContent cedula">
                <label for="cedula" class="labelForm">Cédula: <span id="cedula" name="cedula"></span> </label>
            </div>

            <div class="inputContent nombre">
                <label for="nombre" class="labelForm">Nombre: <span id="nombre" name="nombre"></span> </label>
                <!-- <input type="text" class="inputForm" name="nombre" id="nombre" placeholder="Nombre..."> -->
            </div>
            <div class="inputContent apellido">
                <label for="apellido" class="labelForm">Apellido: <span id="apellido" name="apellido"></span></label>
                <!-- <input type="text" class="inputForm" name="apellido" id="apellido" placeholder="Apellido..."> -->
            </div>

            <div class="inputContent telefono">
                <label for="telefono" class="labelForm">Teléfono: <span id="telefono" name="telefono"></span></label>
                <!-- <input type="number" class="inputForm" name="telefono" id="telefono" placeholder="Teléfono..."> -->
            </div>

            <div class="inputContent email">
                <label for="email" class="labelForm">Email: <span id="email" name="email"></span></label>
                <!-- <input type="email" class="inputForm" name="email" id="email" placeholder="Correo electrónico..."> -->
            </div>

            <div class="inputContent areaDestino">
                <label for="areaDestino" class="labelForm">Área de destino: *</label>
                <select name="areaDestino" id="areaDestino">
                    <option value="">---</option>
                    <option value="centro">Centro</option>
                    <option value="externo">Externo</option>
                </select>
            </div>

            <div class="inputContent fechaReserva">
                <label for="fechaReserva" class="labelForm">Fecha Reserva: *</label>
                <input type="date" class="inputForm" name="fechaReserva" id="fechaReserva">
            </div>

            <div class="inputContent horaInicioFin">
                <div class="horaInicio">
                    <label for="inicio" class="labelForm">Hora inicio:</label>
                    <input type="time" class="inputForm" id="inicio" name="inicio">
                </div>
                <div class="horaFin">
                    <label for="fin" class="labelForm">Hora fin:</label>
                    <input type="time" class="inputForm" id="fin" name="fin">
                </div>
            </div>

            <div class="inputContent fechaDevolucion">
                <label for="fechaDevolucion" class="labelForm">Fecha Devolución: *</label>
                <input type="date" class="inputForm" name="fechaDevolucion" id="fechaDevolucion">
            </div>

            <div class="inputContent inputObservaciones">
                <label for="observaciones" class="labelForm">Observaciones:</label>
                <textarea name="observaciones" class="inputForm" id="observaciones" placeholder="Digite una observación en caso de que sea requerida."></textarea>
            </div>

            <div class="inputAddElements">
                <Span>Seleccione los elementos:</Span>
                <button type="button" id="btnAddElements"></button>
                <button type="button" id="btnAddConsumibles"></button>
            </div>

            <!-- Contenedor que va a tener los elementos que seran prestados -->
            <div class="tableElements">
                <?php require_once 'tablePreviewElements.php'; ?>
            </div>

            <div class="inputBtn">
                <button type="submit" id="btnSubmit"></button>
            </div>
        </form>
    </div>
</div>

<!-- Contenedor que contiene el modal de las tablas de elementos devolutivos y consumibles -->
<div id="addElements">
    <?php require_once 'modalAddDevolutivos.php'; ?>
</div>

<!-- Contenedor que tiene el modal de los elementos consumibles. -->
<div id="addElementsConsumibles">
    <?php require_once 'modalAddConsumibles.php' ?>
</div>

<!-- Contenedor modal que contiene los registros de los usuarios -->
 <div id="users">
    <?php require_once 'tableUsers.php'; ?>
 </div>

<script type="module" src="../public/assets/js/reservaPrestamos/reservaPrestamos.js"></script>
