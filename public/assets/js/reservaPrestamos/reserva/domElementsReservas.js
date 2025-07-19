import { options, instanceModal, getSelector } from "../../utils/cases.js";

//Tables
export const tablesDoom = {
    tblBodyConsumibles: getSelector("#tblBodyConsumibles"),
    tblBodyDevolutivos: getSelector("#tblBodyDevolutions"),
    tblBodyUsers: getSelector("#tblBodyUsers"),
    tblBodyPreviewElements: getSelector("#tblBodyPreviewElements")
};
//Modals
export const modalDoom = {
    modalAddDevolutivos: instanceModal("#modalAddDevolutivos", options),
    modalAddConsumibles: instanceModal("#modalAddConsumible", options),
    modalUsers: instanceModal("#modalUsers", options)
};
//Buttons
export const btnDoom = {
    btnSubmit: getSelector("#btnSubmit"),
    btnAddElements: getSelector("#btnAddElements"),
    btnAddConsumibles: getSelector("#btnAddConsumibles"),
    btnAddUser : getSelector('#btnAddUser'),
    btnModalPreviewElements: getSelector("#previewElements"),
    btnCloseDevolutivos : getSelector("#modalAddDevolutivos .close-modal"),
    btnCloseConsumible : getSelector("#modalAddConsumible .close-modal"),
    btnCloseUsers: getSelector("#modalUsers .close-modal"),
    btnClosePreviewElements: getSelector("#btnClosePreviewElements"),
    btnPreviewUsers : getSelector("#btnPreviewUsers"),
    btnNextUsers: getSelector("#btnNextUsers")
}

// inputsForm
export const inputsForm = {
    areaDestino: getSelector("#areaDestino"),
    horaInicio: getSelector('.horaInicio'),
    horaFin: getSelector('.horaFin'),
    horaInicioFin: getSelector('.horaInicioFin'),
    inputNombre : getSelector("#nombre"),
    inputNroDocumento: getSelector("#cedula"),
    inputApellido: getSelector("#apellido"),
    inputTelefono: getSelector("#telefono"),
    inputEmail: getSelector("#email")

}