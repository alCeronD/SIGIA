
<!-- Vista del tipo de documento -->
    <h1>Vista tipo documento</h1>
    <div class="form">
        <form id="formTp">
            <input type="text" name="tp_sigla" id="nombreArea" placeholder="Sigla tipo documento" value="">
            <textarea name="tp_nombre" placeholder="Nombre tipo documento" id="descripcionArea" value=""></textarea>
            <button type="submit" id="btnAreaUpdate">Registrar</button>
        </form>
    </div>
    <?php require_once 'tableViewtpDocumento.php'; ?>

<!-- Modal -->
<div id="modalTp" class="modal">

    <!-- Modal content -->
    <div class="modal-content-tp">
        <span class="close">&times;</span>
        <span id="modalTitle">Actualizar registro</span>
        <form id="tpUpdateForm">
            <input type="text" name="tp_sigla" id="siglaTp_documento" placeholder="Sigla ...">
            <input type="text" name="tp_nombre" id="descripcionTp_documento" placeholder="Nombre..." ></input>
            <button type="click" id="btnAreaUpdate">Agregar</button>
        </form>
    </div>

</div>

<script type="module" src="../public/assets/js/configModules/tpDocumento.js"></script>