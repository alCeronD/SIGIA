
<div id="modalAddConsumible" class="modal" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-title">
            <span id="modalTitle">Elementos disponibles</span>
            <button type="button" id="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <!-- Tabla de elementos devolutivos -->
        <div class="tableElemConsumible  highlight striped responsive-table">
            <?php include_once 'tablaElmConsumible.php'; ?>
        </div>
    </div>
</div>