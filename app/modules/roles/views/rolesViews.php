<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>

<div class="contentRoles contentLayout">
    <div class="titleRoles menuTitle">
        <span id="textTitleAreas">Roles</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>

    <div class="formRl">
        <?php require_once 'rolesRegistrar.php'; ?>
    </div>

    <div class="tblRoles">
        <table class="table table-bordered table-striped table-responsive">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Descripción</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)) : ?>
                    <?php foreach ($roles as $rol) : ?>
                        <tr>
                            <td><?php echo $rol['rl_id']; ?></td>
                            <td><?php echo htmlspecialchars($rol['rl_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($rol['rl_descripcion']); ?></td>
                            <td>
                                <?php
                                $value = ($rol['rl_status'] == 1) ? 'Activo' : 'Inactivo';
                                echo htmlspecialchars($value);
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="abrirModal('<?php echo $rol['rl_id']; ?>', '<?php echo htmlspecialchars($rol['rl_nombre']); ?>', '<?php echo htmlspecialchars($rol['rl_descripcion']); ?>')">
                                    Editar
                                </button>
                                <a href="<?= getUrl('roles', 'roles', 'eliminarRol', ['rl_id' => $rol['rl_id'], 'rl_status' => $rol['rl_status']], 'dashboard') ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Está seguro de inactivar este registro?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay roles registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de edición -->
<div id="modalEditar" class="modal hidden">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <h2>Editar Rol</h2>
        <form id="formEditarRol" method="POST" action="<?php echo getUrl('roles','roles','editarRol',false,'dashboard'); ?>">
            <input type="hidden" name="rol_id" id="modal_rol_id">

            <label for="modal_rol_nombre">Nombre:</label>
            <input type="text" name="rol_nombre" id="modal_rol_nombre" required>

            <label for="modal_rol_descripcion">Descripción:</label>
            <textarea name="rol_descripcion" id="modal_rol_descripcion" rows="3" required></textarea>

            <button type="submit">Actualizar</button>
        </form>
    </div>
</div>
<!-- Script para manejar el modal -->
<script>
function abrirModal(id, nombre, descripcion) {
    document.getElementById('modal_rol_id').value = id;
    document.getElementById('modal_rol_nombre').value = nombre;
    document.getElementById('modal_rol_descripcion').value = descripcion;
    document.getElementById('modalEditar').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditar').classList.add('hidden');
}

// Opcional: cerrar modal si se hace clic fuera del contenido
document.getElementById('modalEditar').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<style>
.modal {
    position: fixed;
    inset: 0;
    display: grid;
    place-items: center;
    background-color: rgba(0,0,0,0.4);
    z-index: 9999;
}

.modal.hidden {
    display: none !important;
}

.modal-contenido {
    background: white;
    padding: 1.5rem;
    display: grid;
    gap: 0.8rem;
    width: 100%;
    max-width: 400px;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.cerrar {
    justify-self: end;
    cursor: pointer;
    font-size: 1.4rem;
    font-weight: bold;
    color: #333;
    user-select: none;
    transition: color 0.3s ease;
}

.cerrar:hover {
    color: #e74c3c;
}
</style>
