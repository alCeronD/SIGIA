<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Listado de Roles</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre del Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)) : ?>
                <?php foreach ($roles as $rol) : ?>
                    <tr>
                        <td><?php echo $rol['rl_id']; ?></td>
                        <td><?php echo htmlspecialchars($rol['rl_nombre']); ?></td>
                        <td>
                            <a href="index.php?action=rolesEditar&id=<?php echo $rol['rl_id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="index.php?action=rolesEliminar&id=<?php echo $rol['rl_id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Estás seguro de eliminar este rol? Esta acción no se puede deshacer.');">
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
</body>
</html>
