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
                <?php
                if (!empty($roles)) : ?>
                    <?php foreach ($roles as $rol) : ?>
                        <tr>
                            <td><?php echo $rol['rl_id']; ?></td>
                            <td><?php echo htmlspecialchars($rol['rl_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($rol['rl_descripcion']); ?></td>
                            <td><?php echo $value = ($rol['rl_status'] == 1) ? 'Activo' : 'Inactivo';
                                htmlspecialchars($value); ?></td>
                            <td>
                                <a href="<?= getUrl('roles', 'roles', 'editarRolesView', ['rl_id' => $rol['rl_id']], 'dashboard') ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="<?= getUrl('roles', 'roles', 'eliminarRol', ['rl_id' => $rol['rl_id'], 'rl_status' => $rol['rl_status']], 'dashboard') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de inactivar este registro?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay roles registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>