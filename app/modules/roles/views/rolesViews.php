<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>
    <h2 class="mb-4 text-center">Listado de Roles</h2>
    <table class="table table-bordered table-striped" >
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
                        <td><?php echo htmlspecialchars($rol['rl_descripcion']);?></td>
                        <td><?php echo $value = ($rol['rl_status'] == 1) ? 'Activo': 'Inactivo'; htmlspecialchars($value);?></td>
                        <td>
                            <a href="<?= getUrl('roles', 'roles', 'editarRolesView', ['rl_id' => $rol['rl_id']],'dashboard') ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="<?= getUrl('roles', 'roles', 'eliminarRol', ['rl_id' => $rol['rl_id'], 'rl_status' => $rol['rl_status']],'dashboard') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de inactivar este registro?');">
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