<div class="modal" id="modalPreviewElements" style="display: none;">
    <div class="modal-content">
        <div class="previewElements">
            <div class="modal-title">
                <span id="modalTitle"></span>
                <button type="button" id="closeModalBtnPreview">
                    <span class="close-modal">&times;</span>
                </button>
            </div>
            <table id="">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre Elemento</th>
                        <th>Area</th>
                        <th>Cantidad</th>
                        <!-- <th>Acciones</th> -->
                    </tr>
                </thead>
                <tbody id="tableBodyPreviewElements">

                </tbody>

            </table>
            <!-- Si la cantidad de elementos solicitados es mayor, esta debera de ser páginada pero desde el javascript -->
            <div class="buttons">
                <button type="button" class="previewBtn" id="previewElements"><i class="material-icons">keyboard_arrow_left</i></button>
                <button type="button" class="nextBtn" id="nextElements"><i class="material-icons">keyboard_arrow_right</i></button>
            </div>

        </div>
    </div>
</div>