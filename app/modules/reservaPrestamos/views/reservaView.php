<!-- Realizar la reserva -->
<div class="content">
    <div class="menuTitle">
        <span id="textTitle">Gestión de reserva</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
    <div class="searchUser">
        <label for="">Instructor: </label>
        <button type="button" id="btnAddUser" class="btnInfo btnClick btn waves-effect waves-light">
            <i class="material-icons">person_add</i>
        </button>
    </div>
    <div class="solicPrestamos">
        <form id="formSolicitudPrestamo">
            <div class=" inputContent cedula">
                <label class="" for="cedula">Cédula: <span id="cedula" name="cedula" class=""></span></label>
            </div>
            <div class=" inputContent nombre">
                <label for="nombre" class="labelForm">Nombre: <span id="nombre" name="nombre"></span> </label>
            </div>
            <div class=" inputContent apellido">
                <label for="apellido" class="labelForm">Apellido: <span id="apellido" name="apellido"></span></label>
            </div>

            <div class=" inputContent telefono">
                <label for="telefono" class="labelForm">Teléfono: <span id="telefono" name="telefono"></span></label>
            </div>

            <div class=" inputContent email">
                <label for="email" class="labelForm">Email: <span id="email" name="email"></span></label>
            </div>
            <div class="input-field inputContent areaDestino">
                <input id="areaDestino" name="areaDestino" type="text" class="validate">
                <label for="areaDestino">Dirección del destino *</label>
            </div>
            <div class="input-field inputContent fechaReserva">
                <input type="text" class="datepicker" name="fechaReserva" id="fechaReserva" placeholder="Fecha de reserva *">
                <label for="fechaReserva">Fecha de reserva*</label>
            </div>
            <div class="input-field inputContent fechaDevolucion">
                <input type="text" class="datepicker" name="fechaDevolucion" id="fechaDevolucion" placeholder="Fecha Devolución *">
                <label for="fechaDevolucion">Fecha Devolución *</label>
            </div>

            <div class="input-field inputContent inputObservaciones">
                <textarea name="observaciones" class="materialize-textarea inputForm" id="observaciones"></textarea>
                <label for="observaciones" class="labelForm">Observaciones:</label>
            </div>

            <div class="inputAddElements">
                <Span>Seleccione los elementos:</Span>
                <div class="btnItems">
                    <button type="button" class="btn waves-effect waves-light" id="btnAddElements"></button>
                    <button type="button" class="btn waves-effect waves-light" id="previewElements"></button>
                    <button type="button" class="btn waves-effect waves-light" id="btnAddConsumibles"></button>
                </div>
            </div>

            <!-- Contenedor que va a tener los elementos que seran prestados -->
            <div class="tableElements">
                <?php require_once 'modalPreviewElements.php'; ?>
            </div>

            <div class="inputBtn">
                <button type="submit" id="btnSubmit" class="btnSave btn waves-effect waves-light"></button>
            </div>
        </form>
    </div>
</div>

<!-- Contenedor que contiene el modal de las tablas de elementos devolutivos y consumibles -->
<div id="addElements">
    <?php require_once 'modalAddDevolutivos.php'; ?>
</div>

<!-- Contenedor que tiene el modal de los elementos consumibles. -->
<div id="addElementsConsumibles" class="">
    <?php require_once 'modalAddConsumibles.php' ?>
</div>

<!-- Contenedor modal que contiene los registros de los usuarios -->
 <div id="users">
    <?php require_once 'modalAddUsers.php'; ?>
 </div>



<script type="module" src="../public/assets/js/reservaPrestamos/reservaPrestamos.js"></script>
