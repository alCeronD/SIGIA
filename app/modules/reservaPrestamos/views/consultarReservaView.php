<div id="contentConsultar">
    <div id="search">
        <div class="searchInput menuTitle">
            <span id="menuTitleConsult">Reservas y solicitudes</span>
            <!-- <input type="text" name="" id="inputSearch"> -->
        </div>
        <a class="close close-btn"  title="volver a dashboard" href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>">&times;</a>
    </div>
    <div class="reservas">
        <?php require_once 'tablaConsultarSolicitudView.php'; ?>
    </div>
</div>

<div class="detailReserva">
<?php require_once 'modalDetailReserva.php'; ?>
</div>

<script type="module" src="../public/assets/js/reservaPrestamos/consultarReserva.js"></script>