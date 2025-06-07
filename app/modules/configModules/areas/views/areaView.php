<!-- TODO: Mover al archivo específico de la vista de area. -->
<style>
    
</style>


    <h1>Vista areas</h1>
    <div class="form">
        <form id="formArea">
            <input type="text" name="ar_nombre" id="nombreArea" placeholder="Nombre area..." value="">
            <textarea name="ar_descripcion" placeholder="Descripción..." id="descripcionArea" value=""></textarea>
            <button type="submit" id="btnAreaUpdate">Registrar</button>
        </form>
    </div>
    <!-- Tabla de vista. -->
    <?php require_once 'tableViewArea.php'; ?>


<!-- Modal -->
<div id="modalArea" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <span id="modalTitle">Actualizar registro</span>
        <form id="areaUpdateForm">
            <input type="text" name="ar_nombre" id="nombreAreaUpdate" placeholder="Nombre area...">
            <textarea name="ar_descripcion" id="descripcionAreaUpdate" placeholder="Descripción..." ></textarea>
            <button type="click" id="btnAreaUpdate">Agregar</button>
        </form>
    </div>

</div>

<!-- En la vista importar si o si el archivo específico a cada modulo -->
<script type="module" src="../public/assets/js/configModules/areas.js"></script>