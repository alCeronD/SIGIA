<!-- Vista de area -->

<div class="container">
    <h1>Vista areas</h1>
    <div class="form">
        <form id="formArea">
            <input type="text" name="ar_nombre" id="nombreArea" placeholder="Nombre area...">
            <textarea name="ar_descripcion" placeholder="Descripción..." id="descripcionArea"></textarea>
            <button type="submit" id="btnArea">Agregar</button>
        </form>
    </div>
    <!-- Tabla de vista. -->
    <?php require_once 'tableView.php'; ?>
    
</div>

<!-- En la vista importar si o si el archivo específico a cada modulo -->
<script type="module" src="../public/assets/js/configModules/areas.js"></script>