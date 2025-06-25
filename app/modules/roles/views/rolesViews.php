<?php require_once __DIR__ . '/../../../helpers/session.php'; ?>

<div class="contentRoles contentLayout">
    <div class="titleRoles menuTitle">
        <span id="textTitleAreas" class="textTitleSpan">Gestión de roles</span>
        <a href="<?php echo getUrl('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
            class="close-btn"
            title="Volver al dashboard">&times;</a>
    </div>

    <div class="formRl">
        <?php require_once 'rolesRegistrar.php'; ?>
    </div>

    <div class="tblRoles">
        <table class="table table-bordered table-striped table-responsive tblConfigModules">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Descripción</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tableBodyRoles">
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
                                <button type="button" id="btnEditar" data-desc="<?php echo $rol['rl_descripcion']; ?>" data-nombre = "<?php echo $rol['rl_nombre']; ?>" data-id="<?php echo $rol['rl_id']; ?>" class="waves-effect waves-light btn btnEditar">
                                    <i class="material-icons">edit</i>
                                </button>
                                <a href="<?= getUrl('roles', 'roles', 'eliminarRol', ['rl_id' => $rol['rl_id'], 'rl_status' => $rol['rl_status']], 'dashboard') ?>"
                                    class="waves-effect waves-light btn red"
                                    onclick="return confirm('¿Está seguro de inactivar este registro?');">
                                    <i class="material-icons">delete</i>
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
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <div class="titleSection">
            <h2>Editar Rol</h2>

            <button type="button" class="closeModalBtn">
                <span class="close-modal">&times;</span>
            </button>
        </div>
        <div class="formRol">
            <form id="formEditarRol" method="POST" action="<?php echo getUrl('roles','roles','editarRol',false,'dashboard'); ?>">

            <div>
                <input type="hidden" name="rol_id" id="modal_rol_id">
            </div>
            <div class="input-field contentRlNombre">
                <input type="text" name="modal_rol_nombre" id="modal_rol_nombre" required>
                <label for="modal_rol_nombre">Rol:</label>
            </div>

            <div class="input-field"> 
                <textarea name="rol_descripcion" id="modal_rol_descripcion" rows="3" class="materialize-textarea" required></textarea>
                <label for="modal_rol_descripcion">Descripción:</label>
            </div>
            <div class="">
                <button type="submit" class="btnSubmit waves-effect waves-light btn"><i class="material-icons">save</i></button>

            </div>
        </form>
        </div>
        
    </div>
</div>

<script type="module" src="../public/assets/js/roles/roles.js"></script>