<!-- css modal -->
 <style>
    #modalTp {
        display: none;
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        height: auto;
        /* Could be more or less, depending on screen size */
    }

    .close{
        cursor: pointer;
    }
</style>
<!-- Vista del tipo de documento -->

<div class="container">
    <h1>Vista areas</h1>
    <div class="form">
        <form id="formTp">
            <input type="text" name="ar_nombre" id="nombreArea" placeholder="Nombre area..." value="">
            <textarea name="ar_descripcion" placeholder="Descripción..." id="descripcionArea" value=""></textarea>
            <button type="submit" id="btnAreaUpdate">Registrar</button>
        </form>
    </div>
    <?php require_once 'tableViewtpDocumento.php'; ?>

</div>

<!-- Modal -->
<div id="modalTp" class="modal">

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

<script type="module" src="../public/assets/js/configModules/tpDocumento.js"></script>