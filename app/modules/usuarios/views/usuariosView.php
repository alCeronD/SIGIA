
<style>
  .form-container-full {
    width: 100%;
    background:rgb(181, 215, 248);
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
</style>

    <div class="form-container-full">
      <form method="POST" action="<?php echo getUrl('usuarios','usuarios','createUser'); ?>" class="row g-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="usu_docum" class="form-label">Documento</label>
            <input type="text" class="form-control" id="usu_docum" name="usu_docum" required>
          </div>
          <div class="mb-3">
            <label for="usu_nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="usu_nombres" name="usu_nombres" required>
          </div>
          <div class="mb-3">
            <label for="usu_apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="usu_apellidos" name="usu_apellidos" required>
          </div>
          <div class="mb-3">
            <label for="usu_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="usu_direccion" name="usu_direccion" required>
          </div>
          <div class="mb-3">
            <label for="usu_password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="usu_password" name="usu_password" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label for="usu_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="usu_email" name="usu_email" required>
          </div>
          <div class="mb-3">
            <label for="usu_telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="usu_telefono" name="usu_telefono" required>
          </div>
          <div class="mb-3">
            <label for="rol_id" class="form-label">Rol</label>
            <select class="form-select" id="rol_id" name="rol_id" required>
              <option value="">Seleccione un rol</option>
              <?php foreach ($roles as $roli): ?>
                <option value="<?php echo $roli['rl_id']; ?>">
                  <?php echo htmlspecialchars($roli['rl_nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="usu_tp_id" class="form-label">Tipo de documento</label>
            <select class="form-select" id="usu_tp_id" name="usu_tp_id" required>
              <option value="">Seleccione un tipo</option>
              <?php foreach ($rowTp as $tp): ?>
                <option value="<?php echo $tp['tp_id']; ?>">
                  <?php echo htmlspecialchars($tp['tp_sigla'] . " - " . $tp['tp_nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100 py-2">Enviar</button>
        </div>
      </form>
    </div>

