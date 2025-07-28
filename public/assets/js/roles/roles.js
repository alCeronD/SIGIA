import {
  closeModal,
  createI,
  getData,
  initAlert,
  instanceModal,
  openModal,
  options,
  sendData,
  toastOptions,
  validateFormData,
} from "./barrelRoles.js";
// const modalEditar = document.querySelector("#modalEditar");
const closeModalBtn = document.querySelector(".closeModalBtn");

const modalEditar = instanceModal("#modalEditar", options);
const tableBodyRoles = document.querySelector("#tableBodyRoles");
const formEditarRol = document.querySelector("#formEditarRol");
console.log(formEditarRol);

const renderRoles = async () => {
  const responseRoles = await getData(
    "Modules/Roles/Controller/RolesController.php",
    "GET",
    { action: "getRoles" }
  );

  const dataRoles = responseRoles.data;
  tableBodyRoles.innerHTML = "";
  dataRoles.forEach((rl) => {
    let trRoles = document.createElement("tr");
    let tdActions = document.createElement("td");
    let tdID = document.createElement("td");
    let tdNombre = document.createElement("td");
    let tdDescript = document.createElement("td");
    let tdStatus = document.createElement("td");
    let btnEditar = document.createElement("button");
    let btnStatus = document.createElement("button");
    let btnAsig = document.createElement("button");
    btnEditar.setAttribute("type", "button");
    btnEditar.setAttribute("class", "btnEdit");
    btnEditar.setAttribute("data-id", `${rl.rl_id}`);
    btnEditar.setAttribute("data-nombre", `${rl.rl_nombre}`);
    btnEditar.setAttribute("data-desc", `${rl.rl_descripcion}`);
    btnStatus.setAttribute("type", "button");
    btnAsig.setAttribute("type", "button");
    let iconEditar = createI("border_color");
    let iconStatus = createI("delete_sweep");
    let iconAsig = createI("build");
    btnEditar.appendChild(iconEditar);
    btnAsig.appendChild(iconAsig);
    btnStatus.appendChild(iconStatus);
    tdActions.appendChild(btnAsig);
    tdActions.appendChild(btnEditar);
    tdActions.appendChild(btnStatus);
    tdID.innerText = rl.rl_id;
    tdNombre.innerText = rl.rl_nombre;
    tdDescript.innerText = rl.rl_descripcion;
    let valueStatus = rl.rl_status === "1" ? "Activo" : "Inactivo";
    tdStatus.innerText = valueStatus;

    tableBodyRoles.appendChild(trRoles);

    trRoles.append(tdID, tdNombre, tdDescript, tdStatus, tdActions);

    // Boton de editar.
    btnEditar.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      let id = e.target.getAttribute("data-id");
      let descripcion = e.target.getAttribute("data-desc");
      let nombre = e.target.getAttribute("data-nombre");

      let inputId = document.querySelector("#modalEditar #modal_rol_id");
      let inputNombre = document.querySelector(
        "#modalEditar #modal_rol_nombre"
      );
      let inputDescript = document.querySelector(
        "#modalEditar #modal_rol_descripcion"
      );

      inputId.value = id;
      inputNombre.value = nombre;
      inputDescript.value = descripcion;

      //Como cambiamos el valor del formulario, necesitamos volver a reiniciar los input del formulario
      M.updateTextFields();

      modalEditar.open();
    });
  });
};

const mapObj = {
  modal_rol_nombre: "Nombre del Rol",
  rol_descripcion: "Descripción del Rol",
  rol_id: "ID del Rol"
};

console.log({"mapOjb": mapObj});

const optionals = ['rol_descripcion'];

formEditarRol.addEventListener("submit",async (e) => {
  e.preventDefault();
  e.stopPropagation();
  const formEdit = new FormData(e.target);
  const objData = Object.fromEntries(formEdit);
  if (!validateFormData({formData: formEdit, campos: optionals, mapForm: mapObj})) return;

  try {
    const updateInfo = await sendData('Modules/Roles/Controller/RolesController.php', "PUT", "updateRol",objData);
    console.log(updateInfo);
    if (updateInfo.status) {
        renderRoles();
        initAlert("Recurso actualizado", "succes", toastOptions);
        modalEditar.close();
    }
  } catch (error) {
    initAlert("Error al realizar la actualización", "error", `${error}`);
  }

});

document.addEventListener("DOMContentLoaded", () => {
  renderRoles();
  closeModal(modalEditar, closeModalBtn);
  
});
