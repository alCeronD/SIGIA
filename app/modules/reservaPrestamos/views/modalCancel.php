<!-- Modal para digitar la observación en caso de que sea necesaria -->
<div id="modalCancel" class="modal" style="display: none;">
    <div class="modal-content " id="modalContentCancel">
        <div class="modal-title modalTitleCancel">
            <span id="modalTitleCancel"></span>
            <button type="button" id="btnCloseCancel">
                <span class="close-modal">&times;</span>
            </button>
        </div>
            <form id="formCancel">
                <div class="col s12">
                    <label for="radioValidate">¿Deseas realizar una observación? *</label>
                    <div class="inputsRadio">
                        <p>
                            <label>
                                <input id="radioYes" class="with-gap" name="radioCancel" value="on" type="radio" />
                                <span>Si</span>
                            </label>
                        </p>
                        <!-- validateNo -->
                        <p>
                            <label>
                                <input id="radioNo" class="with-gap" name="radioCancel" value="off" type="radio" />
                                <span>No</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="input-field col s12" id="textAreaObservacion">
                    <textarea id="inputObservacionCancel" name="observacion" class="materialize-textarea" readonly></textarea>
                    <label for="textarea1" id="observacionesLabelCancel" class="">Observación: </label>
                    <button type="submit" class="btn waves-effect waves-light" id="submitSalida"><i class="material-icons">send</i></button>
                </div>
            </form>

    </div>
</div>