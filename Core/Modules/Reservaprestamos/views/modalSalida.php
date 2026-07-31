<!-- Modal para digitar la observación en caso de que sea necesaria -->
<div id="modalSalida" class="modal" style="display: none;">
    <div class="modal-content " id="modalContentSalida">
        <div class="modal-title modalTitleSalida">
            <span id="modalTitle">Reserva #</span>
            <button type="button" id="closeModalBtnSalida">
                <span class="close-modal">&times;</span>
            </button>
        </div>

            <form id="formSalida">
                <div class="col s12">
                    <label for="radioValidate">¿Deseas realizar una observación? *</label>
                    <div class="inputsRadio">
                        <p>
                            <label>
                                <input id="radioYes" class="with-gap" name="radioSalida" value="on" type="radio" />
                                <span>Si</span>
                            </label>
                        </p>
                        <!-- validateNo -->
                        <p>
                            <label>
                                <input id="radioNo" class="with-gap" name="radioSalida" value="off" type="radio" />
                                <span>No</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="input-field col s12" id="textAreaObservacion">
                    <textarea id="inputObservacionsalida" name="observacion" class="materialize-textarea" readonly></textarea>
                    <label for="textarea1" id="observacionesLabelSalida" class="">Observación: </label>
                    <button type="submit" class="btn waves-effect waves-light" id="submitSalida"><i class="material-icons">send</i></button>
                </div>
            </form>

    </div>
</div>