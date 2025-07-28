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
                <!-- Renderizado con javascript -->
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'modalEditar.php';?>
<?php require_once 'modalAsing.php'; ?>

<!-- modal de confirmación -->
<?php require_once __DIR__ .'/../../../helpers/modalConfirmation.php'; ?>

<script type="module" src="../public/assets/js/roles/roles.js"></script>