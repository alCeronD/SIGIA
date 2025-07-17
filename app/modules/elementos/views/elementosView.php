<div class="container-fluid px-4">
    <div class="row valign-wrapper" style="margin-bottom: 20px;">
        <!-- Título -->
        <div class="col s12 m7">
            <h5 style="margin: 0;">Gestión de elementos</h5>
        </div>

        <!-- Botón de registro -->
        <div class="col s12 m6 center">
            <button type="button" class="waves-effect waves-light btn" id="btnAddModalElements"></button>
        </div>

        <!-- El filtro -->
        <div class="col s12 m2">
            <div class="input-field" style="margin: 0;">
                <select id="filtroTipo">
                    <option value="all" selected>Todos</option>
                    <option value="devolutivo">Devolutivo</option>
                    <option value="consumible">Consumible</option>
                </select>
            </div>
        </div>

        <!-- Input de búsqueda -->
        <div class="col s12 m2">
            <input id="inputBusqueda" type="text" placeholder="Buscar...">
        </div>

        <!-- Botón de buscar -->
        <div class="col s12 m2">
            <a id="btnBuscar" class="waves-effect waves-light btn">Buscar</a>
        </div>
    </div>
    <div class="table table-responsive-fixed">
        <table class="table table-striped table-bordered text-center align-middle" id="tblElements">
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
    <tfoot>
        <tr>
            <td colspan="9">
                <ul class="pagination center-align" style="margin-top: 20px;">
                    <!-- Botón Anterior -->
                    <li class="waves-effect">

                        <button type="button" class="waves-effect btn" id="previewElements">
                            <i class="material-icons">chevron_left</i>

                        </button>
                    </li>
                    <!-- Botón Siguiente -->
                    <li class="waves-effect">
                        <button type="button" class="waves-effect btn" id="nextElements">
                            <i class="material-icons">chevron_right</i>
                        </button>
                    </li>
                </ul>
            </td>
        </tr>
    </tfoot>
</div>

<?php 
require_once 'modalElementos.php'; 
require_once 'modalEditarElemento.php';
require_once 'modalVerMas.php';
require_once 'modalConfirmacion.php';
require_once 'modalAddExistencia.php';
?>

<script type="module" src="../public/assets/js/elementos/elementosNew.js"></script>