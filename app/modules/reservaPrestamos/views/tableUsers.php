<div class="modal" id="modalUsers" style="display: none;">
    <div class="modal-content">
        <div class="modal-title">
            <span id="modalTitle">Instructores</span>
                <button type="button" id="closeModalBtn">
                    <span class="close-modal">&times;</span>
                </button>
        </div>
        <div class="tableContent highlight striped responsive-table">
            <table>
                <thead>
                    <tr>
                        <td>Cedula</td>
                        <td>Nombre</td>
                        <td>Apellido</td>
                        <td>Teléfono</td>
                        <td>Email</td>
                        <td>Rol</td>
                        <td>Acciones</td>
                    </tr>
                </thead>
                <tbody id="tableBodyUsers">
                    <!-- Renderizado con javascript. -->
                </tbody>
            </table>
            <!-- Contenedor que contiene los botones previews y next de la Página -->
            <div class="buttons">
                <input type="button" value="<" id="preview">
                <input type="button" value=">" id="next">
            </div>
        </div>
    </div>
</div>