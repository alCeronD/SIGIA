    <div class="card-content">
      <span class="card-title">Editar información
        <a href="<?php echo getUrl('usuarios', 'usuarios', 'getAll'); ?>" class="btn-flat right" title="Volver al listado">
          <i class="material-icons">close</i>
        </a>
      </span>

      <form method="POST" action="<?= getUrl('usuarios', 'usuarios', 'updateUserInfo', false, 'dashboard') ?>" id="formUpdateUserView">
        <input type="hidden" name="usu_id" value="<?= htmlspecialchars($usuarioUpdate['usu_id']) ?>">

        <div class="row">
          <div class="input-field col s12 m6">
            <input id="usu_docum" type="text" name="usu_docum" value="<?= htmlspecialchars($usuarioUpdate['usu_docum']) ?>" disabled>
            <label for="usu_docum" class="active">Documento</label>
          </div>

          <div class="input-field col s12 m6">
            <input id="usu_nombres" type="text" name="usu_nombres" value="<?= htmlspecialchars($usuarioUpdate['usu_nombres']) ?>" required>
            <label for="usu_nombres" class="active">Nombres</label>
          </div>

          <div class="input-field col s12 m6">
            <input id="usu_apellidos" type="text" name="usu_apellidos" value="<?= htmlspecialchars($usuarioUpdate['usu_apellidos']) ?>" required>
            <label for="usu_apellidos" class="active">Apellidos</label>
          </div>

          <div class="input-field col s12 m6">
            <input id="usu_email" type="email" name="usu_email" value="<?= htmlspecialchars($usuarioUpdate['usu_email']) ?>" required>
            <label for="usu_email" class="active">Correo electrónico</label>
          </div>

          <div class="input-field col s12 m6">
            <input id="usu_direccion" type="text" name="usu_direccion" value="<?= htmlspecialchars($usuarioUpdate['usu_direccion']) ?>" required>
            <label for="usu_direccion" class="active">Dirección</label>
          </div>

          <div class="input-field col s12 m6">
            <input id="usu_telefono" type="text" name="usu_telefono" value="<?= htmlspecialchars($usuarioUpdate['usu_telefono']) ?>" required>
            <label for="usu_telefono" class="active">Teléfono</label>
          </div>
        </div>

        <div class="center-align">
          <button type="submit" class="btn waves-effect waves-light">
            Actualizar Usuario
            <i class="material-icons right">save</i>
          </button>
        </div>
      </form>
    </div>
