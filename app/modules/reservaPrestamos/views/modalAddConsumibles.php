<!-- Este archivo va a contener una tabla en la que mi idea principal es visualizar los elementos que el usuario instructor puede solicitar. -->
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
        <div class="tableElemConsumible">
            <?php include_once 'tablaElmConsumible.php'; ?>
        </div>
    </div>
</div>