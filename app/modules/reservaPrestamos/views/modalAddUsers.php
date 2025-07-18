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
                <tbody id="tblBodyUsers">
                    <!-- Renderizado con javascript. -->
                </tbody>
            </table>
            <!-- Contenedor que contiene los botones previews y next de la Página -->
            <div class="buttons">
                <button type="button" class="previewBtn" id="preview"><i class="material-icons">keyboard_arrow_left</i></button>
                <button type="button" class="nextBtn" id="next"><i class="material-icons">keyboard_arrow_right</i></button>
            </div>
        </div>
    </div>
</div>