<!-- <h2 class="mb-4 text-center">Registrar Nuevo Rol</h2> -->
<div class="card z-depth-2">
            <div class="card-content">
            <p class="flow-text card-title">Registrar roles</p>
<form action="<?php echo getUrl('roles','roles','registrarRol',false,'dashboard') ?>" method="POST" id="formRol">
    
    <!-- Campo: Nombre del Rol -->
    <div class="input-field contentRlNombre">
        <input type="text" id="rol_nombre" name="rol_nombre" required>
        <label for="rol_nombre">Nombre del Rol</label>
    </div>

    <!-- Campo: Descripción del Rol -->
    <div class="input-field contentRlDescript">
        <textarea id="rol_descripcionInput" name="rol_descripcion" class="materialize-textarea" rows="3"></textarea>
        <label for="rol_descripcionInput">Descripción</label>
    </div>

    <!-- Botón -->
    <div class="center-align contentRlBtn">
        <button type="submit" class="btn waves-effect waves-light"><i class="material-icons">send</i></button>
    </div>
    
</form>
</div>
    </div>