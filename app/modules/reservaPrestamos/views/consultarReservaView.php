<div id="contentConsultar">
    <div id="search">
        <div class="searchInput menuTitle">
            <span id="menuTitleConsult" class="">Gestion de Reservas y solicitudes</span>
        </div>
        
        <div class=" filterTipoReserva">
            <span>Filtrar reservas:</span>
            <select id="filtroTipoReserva">
                <option value="todos" selected>Todos</option>
                <option value="porValidar">Por Validar</option>
                <option value="validado">Validado</option>
                <option value="finalizado">Finalizados</option>
            </select>
        </div>
        <div class="closeItem">
            <a class="close close-btn" title="volver a dashboard" href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>">&times;</a>

        </div>

    </div>
    <div class="reservas">
        <?php require_once 'tablaConsultarSolicitudView.php'; ?>
        <div class="pages">
            <div class="previewReservas">
                <button type="button" id="previewReservas" class="btn-small btn waves-effect waves-light"><i class="material-icons">chevron_left</i></button>
            </div>
            <div class="nextReservas">
                <button type="button" id="nextReservas" class="btn-small btn waves-effect waves-light"><i class="material-icons">chevron_right</i></button>
            </div>
        </div>
    </div>
</div>

<div class="detailReserva">
    <?php require_once 'modalDetailReserva.php'; ?>
</div>

<!-- Contenedor modal que contiene el modal para validar la data -->
<div id="validateElements">
    <?php require_once 'modalValidate.php'; ?>
</div>

<script type="module" src="../public/assets/js/reservaPrestamos/consultarReserva.js"></script>