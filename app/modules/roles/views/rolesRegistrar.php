<!-- <h2 class="mb-4 text-center">Registrar Nuevo Rol</h2> -->
<form action="<?php echo getUrl('roles','roles','registrarRol',false,'dashboard') ?>" method="POST" class="formLayout" id="formRol">
    <div class="mb-3 contentRlNombre">
        <label for="rol_nombre" class="form-label">Nombre del Rol:</label>
        <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" required>
    </div>
    <div class="mb-3 contentRlDescript">
        <label for="rol_descripcion" class="form-label">Descripcion:</label>
        <textarea class="form-control" id="rol_descripcionInput" name="rol_descripcion" rows="3" placeholder="Descripción rol..."></textarea>
    </div>
    <div class="text-center contentRlBtn">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</form>
