<!-- la función getUrl se puede acceder porque la tenemos incluida directamente en el dashboard. -->

<div class="content">
    <div class="menuTitle">
<<<<<<< HEAD
        <span id="textTitle">Registrar Prestamo</span>
=======
        <span id="textTitle">Registrar solicitud</span>
>>>>>>> 90bfcc2 (Home y elementos)
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>
<<<<<<< HEAD
    <div class="searchUser">
        <label for="">Instructor: </label>
        <button type="button" id="searchBtn"></button>
    </div>
    <div class="solicPrestamos">
        <form action="" method="post" id="formSolicitudPrestamo">
            <div class="inputContent cedula">
                <label for="cedula" class="labelForm">Cédula: </label>
=======
    <div id="solicPrestamos">
        <form action="" method="post" id="formSolicitudPrestamo">
            <div class="inputContent cedula">
                <label for="cedula" class="labelForm">Cédula: *</label>
>>>>>>> 90bfcc2 (Home y elementos)
                <input type="number" class="inputForm" name="cedula" id="cedula" placeholder="Identificación...">
            </div>

            <div class="inputContent nombre">
<<<<<<< HEAD
                <label for="nombre" class="labelForm">Nombre: </label>
=======
                <label for="nombre" class="labelForm">Nombre: *</label>
>>>>>>> 90bfcc2 (Home y elementos)
                <input type="text" class="inputForm" name="nombre" id="nombre" placeholder="Nombre...">
            </div>

            <div class="inputContent apellido">
<<<<<<< HEAD
                <label for="apellido" class="labelForm">Apellido: </label>
=======
                <label for="apellido" class="labelForm">Apellido: *</label>
>>>>>>> 90bfcc2 (Home y elementos)
                <input type="text" class="inputForm" name="apellido" id="apellido" placeholder="Apellido...">
            </div>

            <div class="inputContent telefono">
<<<<<<< HEAD
                <label for="telefono" class="labelForm">Teléfono: </label>
=======
                <label for="telefono" class="labelForm">Teléfono: *</label>
>>>>>>> 90bfcc2 (Home y elementos)
                <input type="tel" class="inputForm" name="telefono" id="telefono" placeholder="Teléfono...">
            </div>

            <div class="inputContent email">
                <label for="email" class="labelForm">Email:</label>
                <input type="email" class="inputForm" name="email" id="email" placeholder="Correo electrónico...">
            </div>

            <div class="inputContent areaDestino">
                <label for="areaDestino" class="labelForm">Área de destino: *</label>
                <select name="areaDestino" id="areaDestino">
<<<<<<< HEAD
                    <option value="---">---</option>
=======
                    <option value="">---</option>
>>>>>>> 90bfcc2 (Home y elementos)
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

<<<<<<< HEAD
            <div class="inputContent inputObservaciones">
=======
            <div class="inputObservaciones">
>>>>>>> 90bfcc2 (Home y elementos)
                <label for="observaciones" class="labelForm">Observaciones</label>
                <textarea name="observaciones" class="inputForm" id="observaciones" placeholder="Digite una observación en caso de que sea requerida."></textarea>
            </div>

            <div class="inputAddElements">
                <button type="button" id="btnAddElements"></button>
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
<<<<<<< HEAD

=======
>>>>>>> 90bfcc2 (Home y elementos)
<div id="addElements">
    <?php require_once 'viewAddElements.php'; ?>
</div>

<<<<<<< HEAD
<!-- Contenedor modal que contiene los registros de los usuarios -->
 <div id="users">
    <?php require_once 'tableUsers.php'; ?>
 </div>

<script type="module" src="../public/assets/js/reservaPrestamos/reservaPrestamos.js"></script>
=======
<script type="module" src="../public/assets/js/reservaPrestamos/reservaPrestamos.js"></script>
>>>>>>> 90bfcc2 (Home y elementos)
