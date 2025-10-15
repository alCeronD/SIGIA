<div class="contentElements">
    <!-- <div class=" row valign-wrapper" style="margin-bottom: 20px;"> -->
    <div class="headerElementsModal">
        <!-- Título -->
        <div class="tituloHeaderElement">
            <span>Gestión de elementos</span>
        </div>

        <!-- Botón de registro -->
        <div class="btnAddHeaderElement">
            <button type="button" class="waves-effect waves-light btn" id="btnAddModalElements"></button>
        </div>

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
        <div class="buscarHeaderElement">
            <input id="inputBusqueda" type="text" placeholder="Buscar elementos">
        </div>
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
            <!-- FILA DE PAGINACION DENTRO DE LA TABLA -->
        </table>
    </div>
    <div class="footerElements">
        <div class="notes">
            <span id="totalPages"></span>
        </div>
        <div class="pages">
            <button type="button" class="waves-effect btn" id="previewElements">
                <i class="material-icons">chevron_left</i>
            </button>
            <button type="button" class="waves-effect btn" id="nextElements">
                <i class="material-icons">chevron_right</i>
            </button>
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

<script type="module" src="../public/assets/js/elementos/elementos.js"></script>