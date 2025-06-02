

    <h2 class="mb-4 text-center">Editar Rol</h2>

    <form action="index.php?action=rolesEditar" method="POST">
        <input type="hidden" name="rol_id" value="<?php echo $rol_actual['rl_id']; ?>">

        <div class="mb-3">
            <label for="rol_nombre" class="form-label">Nombre del Rol</label>
            <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" value="<?php echo $rol_actual['rl_nombre']; ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
    </form>

<?php
include_once '../proyecto_sigia/public/partials/footer.php';

?>
