<div class="container">
    <div class="contentElements">
        <div class="headerContent">
            <!-- Título -->
            <?php include_once BASE_PATH . '/../../public/partials/breadCrumbs.php'; ?>
            <div class="titleRolesRunciones menuTitle">
                <span id="textTitleAreas" class="textTitleSpan">Gestión de elementos del sistema</span>
                <!-- agregar elemento nuevo -->
                <button type="button" class="waves-effect waves-light btn" id="btnAddModalElements">+</button>
                <a href="<?php echo Router::createRoute(CR_DASHBOARD, CR_DASHBOARD, CR_DASHBOARD_LOWER_CASE, false, CR_DASHBOARD_LOWER_CASE); ?>"
                    class="close-btn"
                    title="Volver al dashboard">&times;</a>


            </div>
            <!-- <div class="btnAddHeaderElement">
                <button type="button" class="waves-effect waves-light btn" id="btnAddModalElements">+</button>
            </div> -->
            <!-- Botón de registro -->

            <!-- El filtro -->
            <div class="filtroHeaderElement">
                <div class="input-field">
                    <select id="filtroTipo">
                        <option value="all" selected>Todos</option>
                        <option value="devolutivo">Devolutivo</option>
                        <option value="consumible">Consumible</option>
                    </select>
                    <label>Filtro</label>
                </div>
            </div>
            <!-- Input de búsqueda -->
            <!-- <div class="buscarHeaderElement">
                <input id="inputBusqueda" type="text" placeholder="Buscar elementos">
            </div> -->
        </div>
        <div class="contentTableElements">
            <table class="table table-striped table-bordered table-responsive-fixed text-center align-middle" id="tblElements">
                <thead class="table-light">
                    <tr>
                        <th>Placa</th>
                        <th>Nombre</th>
                        <th>Existencia</th>
                        <th>Unidad de Medida</th>
                        <th>Tipo de Elemento</th>
                        <th>Estado</th>
                        <th>Área</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyElements">
                </tbody>
            </table>
        </div>
        <div class="footerElements">
            <!-- <div class="notes">
                <span id="totalPages"></span>
            </div>
            <div class="pages">
                <button type="button" class="waves-effect btn" id="previewElements">
                    <i class="material-icons">chevron_left</i>
                </button>
                <button type="button" class="waves-effect btn" id="nextElements">
                    <i class="material-icons">chevron_right</i>
                </button>
            </div> -->
        </div>
    </div>
</div>

<?php
require_once 'modalElementos.php';
require_once 'modalEditarElemento.php';
require_once 'modalVerMas.php';
require_once 'modalConfirmacion.php';
require_once 'modalAddExistencia.php';
?>