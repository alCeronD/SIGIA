<!-- Este archivo va a contener una tabla en la que mi idea principal es visualizar los elementos que el usuario instructor puede solicitar. -->
<div id="modalAddElements" class="modal" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div id="modal-title">
            <span id="modalTitle">Elementos disponibles</span>
            <button type="button" id="closeModalBtn">
<<<<<<< HEAD
                <span class="close-modal">&times;</span>
=======
                <span class="close">&times;</span>
>>>>>>> 90bfcc2 (Home y elementos)
            </button>
        </div>
        <!-- Tabla de elementos devolutivos -->
        <div class="tableElmDevolutivos">
            <?php include_once 'tablaElmDevolutivos.php'; ?>
        </div>
        <!-- Tabla de elementos consumibles -->
        <div class="tablaElmConsumible">
            <?php //include_once 'tablaElmConsumible.php'; ?>
        </div>
    </div>
</div>