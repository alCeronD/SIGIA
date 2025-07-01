<div id="contentConsultar">
    <div id="search">
        <div class="searchInput menuTitle">
            <span id="menuTitleConsult" class="">Gestion de Reservas y solicitudes</span>
            <!-- <input type="text" name="" id="inputSearch"> -->
        </div>
        <a class="close close-btn"  title="volver a dashboard" href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>">&times;</a>
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

<script type="module" src="../public/assets/js/reservaPrestamos/consultarReserva.js"></script>