<div class="container">
    <div class="contentRoles contentLayout">
        <div class="titleRoles menuTitle">
            <span id="textTitleAreas" class="textTitleSpan">Gestión de roles</span>
            <a href="<?php echo Router::createRoute('dashboard', 'dashboard', 'dashboard', false, 'dashboard'); ?>"
                class="close-btn"
                title="Volver al dashboard">&times;</a>
        </div>

        <div class="formRl">
            <?php require_once 'rolesRegistrar.php'; ?>
        </div>

        <div class="tblRoles">
            <table class="table table-bordered table-striped table-responsive tblConfigModules">
                <thead class="table-dark" id="headerRoles">
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

    <?php require_once 'modalEditar.php'; ?>
    <?php require_once 'modalAsing.php'; ?>

    <!-- modal de confirmación -->
    <?php require_once __DIR__ . '/../../../Helpers/modalConfirmation.php'; ?>

</div>