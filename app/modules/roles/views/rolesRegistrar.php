<h2 class="mb-4 text-center">Registrar Nuevo Rol</h2>
<form action="index.php?action=rolesRegistrar" method="POST">
    <div class="mb-3">
        <label for="rol_nombre" class="form-label">Nombre del Rol</label>
        <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" required>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</form>