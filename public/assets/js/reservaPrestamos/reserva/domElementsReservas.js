import { options, instanceModal, getSelector, createI } from "../../utils/cases.js";

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
    modalUsers: instanceModal("#modalUsers", options),
    modalPreviewElements: instanceModal("#modalPreviewElements", options)

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

// <i> elements to add materialIcons
export const iDom = {
    iCreatePreview : createI("info"),
    iAddUser: createI("add"),
    iAddElement: createI("add_a_photo"),
    iAddConsumible: createI("battery_std"),
    iSendReserva: createI("send")
}

// inputsForm
export const inputsForm = {
    areaDestino: getSelector("#areaDestino"),
    inputNombre : getSelector("#nombre"),
    inputNroDocumento: getSelector("#cedula"),
    inputApellido: getSelector("#apellido"),
    inputTelefono: getSelector("#telefono"),
    inputEmail: getSelector("#email")

}

// Este proceso lo hago por si requiero usar el objeto en otro archivo.
export let objDataConsumibles = [];
// export const objDataDevolutivos = {};
export let objDataUsers = [];