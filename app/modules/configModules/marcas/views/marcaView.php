<style>
    #modalMarca {
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



<form action="" id="marcaForm">
    <input type="text" name="ma_nombre" id="nombreMarca" placeholder="Nombre marca">
    <input type="text" name="ma_descripcion" id="descripcionMarca" placeholder="Descripción marca">
    <button type="button">Guardar</button>
</form>
<?php require_once 'tableMarcaView.php'; ?>

<!-- Modal -->
<div id="modalMarca" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <span id="modalTitle">Actualizar registro</span>
        <form id="marcaUpdateForm">
            <input type="text" name="ma_nombre" id="nombreAreaUpdate" placeholder="Nombre area...">
            <textarea name="ma_descripcion" id="descripcionAreaUpdate" placeholder="Descripción..." ></textarea>
            <button type="click" id="btnAreaUpdate">Agregar</button>
        </form>
    </div>

</div>


<script type="module" src="../public/assets/js/configModules/marcas.js"></script>