<!-- listado de roles del sistema -->
<div class="container">
    <div class="contentRoles">
        <div class="headerContent">
            <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
            <div class="titleRoles menuTitle">
                <span id="textTitleAreas" class="textTitleSpan">Gestión de roles</span>
                <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
                    class="close-btn"
                    title="Volver al dashboard">&times;</a>
            </div>
        </div>

        <div class="formRl">
            <p class="flow-text card-title">Registrar roles</p>
            <form id="formRol">
                <div class="input-field contentRlNombre">
                    <input type="text" id="rol_nombre" name="rl_nombre">
                    <label for="rl_nombre">Nombre del Rol *</label>
                </div>
                <div class="input-field contentRlDescript">
                    <textarea id="rol_descripcionInput" name="rl_descripcion" class="materialize-textarea" rows="3"></textarea>
                    <label for="rl_descripcion">Descripción</label>
                </div>
                <div class="center-align contentRlBtn">
                    <button type="submit" class="btnSubmitRol btn waves-effect waves-light"><i class="material-icons">send</i></button>
                </div>
            </form>
        </div>

        <div class="tblRoles">
            <table class="table table-bordered table-striped table-responsive">
                <thead class="table-dark" id="headerRoles">
                    <tr>
                        <th id="rl_id">ID</th>
                        <th id="rl_nombre">Nombre del Rol</th>
                        <th id="rl_descripcion">Descripción</th>
                        <th id="rl_status">Status</th>
                        <th id="">Acciones</th>
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