    <h2 class="mb-4 text-center">Editar Rol</h2>
    <form action="<?php echo getUrl('roles','roles','editarRol',false,'dashboard'); ?>" method="POST">
        <input type="hidden" name="rol_id" value="<?php echo $resultado['rl_id']; ?>">
        <div class="mb-3">
            <label for="rol_nombre" class="form-label">Nombre:</label>
            <input type="text" placeholder="Nombre" class="form-control" id="rol_nombre" name="rol_nombre" value="<?php echo $resultado['rl_nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="rol_descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="rol_descripcion" id="rol_descripcion" rows="3"><?php echo $resultado['rl_descripcion']; ?></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
    </form>