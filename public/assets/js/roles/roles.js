import { addClassItem } from "../utils/cases.js";
import {
  closeModal,
  createCheckboxGeneric,
  createI,
  getData,
  initAlert,
  instanceModal,
  mostrarConfirmacion,
  openModal,
  options,
  sendData,
  toastOptions,
  validateFormData,
} from "./barrelRoles.js";
const closeModalBtnAsing = document.querySelector("#closeModalBtnAsing");
const closeModalBtnEdit = document.querySelector("#closeModalBtnEdit");
const modalEditar = instanceModal("#modalEditar", options);
const modalAsing = instanceModal("#modalAsingPermisos", options);
// Div contenedor en donde se va a renderizar toda la información.
const asigPermisosContent = document.querySelector("#asigPermisosContent");
const tableBodyRoles = document.querySelector("#tableBodyRoles");
const formEditarRol = document.querySelector("#formEditarRol");
const modalConfirmacion = instanceModal("#modalConfirmacion", options);
const formRol = document.querySelector("#formRol");
let rolId = null;
// Botón de pre confirmación del elemento.
const preconfirmButton = document.querySelector("#preconfirmButton");
// Función para traer las funciones que tiene asociadas el ROL, esta función sirve para verificar que si el permiso está asociado, seleccionar el checkbox automaticamente.
const getPermisosRolAsig = async ({ data = null } = {}) => {
  const responseData = await getData(
    "Modules/Roles/Controller/RolesController.php",
    "GET",
    { action: "getPermisosRolAsig", idRol: data }
  );
  return responseData;
};

let functionIdsAssoc = new Set();
// Funciones descartadas para enviar a eliminar.
let functionDesc = new Set();
/**
 * Actualiza la selección de funciones asociadas a un rol.
 *
 * Si `isAdd` es `true`, agrega el ID de la función a la colección `functionIdsAssoc`.
 * Si `isAdd` es `false`, elimina el ID de la función de `functionIdsAssoc` y lo agrega a `functionDesc`.
 *
 * @param {number|string|null} idFuncion - El ID de la función que se va a agregar o quitar. Puede ser `null`.
 * @param {boolean} [isAdd=true] - Indica si se debe agregar (`true`) o quitar (`false`) la función.
 */
const updateFunctionSelection = (idFuncion = null, isAdd = true) => {
  // IDS DE las funciones asociadas al rol que se selecciono.
  if (isAdd) {
    functionIdsAssoc.add(idFuncion);
  } else {
    functionIdsAssoc.delete(idFuncion);
    functionDesc.add(idFuncion);
  }
};

// Función para traer los roles y las funciones junto a su modulo.
const renderRolesFunciones = async ({ rolesPermisos = [] } = {}) => {
  const getRlFunciones = await getData(
    "Modules/Roles/Controller/rolesController.php",
    "GET",
    { action: "getRolesPermisos" }
  );

  const rolesYFunciones = getRlFunciones.data.data.funciones;
  const modulos = getRlFunciones.data.data.modulos;
  asigPermisosContent.innerHTML = "";
  Object.entries(rolesYFunciones).forEach(([nombreModulo, funcionesModulo]) => {
    // Contenedor del modulo, Acá va toda la estructura, la de la las funciones y el modulo.
    const contenedorModulo = document.createElement("div");
    // Clase base de contenedor para cada elemento.
    contenedorModulo.classList.add("modulo");
    const contendorSpanModulo = document.createElement("div");
    contendorSpanModulo.classList.add("spanNameModule");
    const spanNombreModulo = document.createElement("span");
    spanNombreModulo.innerText = nombreModulo;

    // Contenedor de funciones
    const divFunciones = document.createElement("div");
    divFunciones.classList.add("funciones");
    // agrego el contenedor del titulo al contenedor principal.
    contenedorModulo.appendChild(contendorSpanModulo);
    // Al contenedor del título del modulo le agrego su texto y su checkbox.
    contendorSpanModulo.appendChild(spanNombreModulo);

    /**
     * ciclar los modulos traidos desde el get data para agregarle su id.
     * Esto lo puedo cambiar usando map.
     */
    modulos.forEach((element) => {
      if (element.nombre_Modulo === nombreModulo) {
        const pCheckbox = createCheckboxGeneric({
          value: element.idModulo,
          classItem: "checkboxModule",
        });
        contendorSpanModulo.appendChild(pCheckbox);
      }
    });
    /**
     * Creamos un set para implementar los ids de las funciones asociadas al rol, esto para hacer búsquedas rápidas del y así aplicar el valor checked al input, buscar para que sirve IntersectionObserver (api de javascript).
     */
    const funcionesAsignadas = new Set(rolesPermisos.map((rp) => rp.idFuncion));
    funcionesModulo.forEach((funcion) => {
      // Contenedor de cada función con checkbox
      const contenedor = document.createElement("div");
      contenedor.classList.add("funcionNameCheckbox");
      // usamos el método has para validar si el id de la función presente lo tiene el set de las funciones asignadas, así si existe, validamos su true or false para que el checkbox quede seleccionado.
      let check = funcionesAsignadas.has(funcion.idFuncion);
      if (check) {
        updateFunctionSelection(funcion.idFuncion, true);

      }
      const checkBoxNmGeneric = createCheckboxGeneric({
        text: funcion.nmFuncionUser,
        value: funcion.idFuncion,
        checkedValue: check,
        classItem: "checkboxFunciones",
        data: funcion.idModulo,
      });

      // Agregar al contenedor
      contenedor.appendChild(checkBoxNmGeneric);
      // Agregar al contenedor general
      divFunciones.appendChild(contenedor);
    });

    // Estructura de los contenedores.
    contenedorModulo.appendChild(divFunciones);
    asigPermisosContent.appendChild(contenedorModulo);
  });
};

// Delegación de responsabilidad a las funciones asociados al rol.
asigPermisosContent.addEventListener("change", (e) => {
  const evento = e.target;
  // Ejecutar la el proceso basado en las funciones del checkbox
  if (evento.classList.contains("checkboxFunciones")) {
    const valueCheckbox = parseInt(evento.value);
    if (evento.checked) {
      updateFunctionSelection(parseInt(valueCheckbox), true);
    } else {
      updateFunctionSelection(parseInt(valueCheckbox), false);

    }
  }

  // Ejecutar el proceso basado en el checkbox del modulo.
  if (evento.classList.contains("checkboxModule")) {
    const idModulo = evento.value;

    // Todos los checkbox asociados al modulo, otra forma es también leer la data usando dataset.
    const checkboxesFuncion = Array.from(
      document.querySelectorAll(".checkboxFunciones")
    ).filter(
      (cbFuncion) => cbFuncion.getAttribute("data-idModulo") === idModulo
    );
    checkboxesFuncion.forEach((check) => {
      if (evento.checked) {
        check.checked = true;
        updateFunctionSelection(parseInt(check.value), true);
      } else {
        check.checked = false;
        updateFunctionSelection(parseInt(check.value), false);
      }
    });
  }
});

// Evento de pre confirmación de eventos.
preconfirmButton.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  modalConfirmacion.open();
  // Evento de confirmación para enviar la data o no.
  mostrarConfirmacion(
    "Roles y permisos",
    "Estas seguro de asignar estos permisos al usuario",
    async (responseModal) => {
      if (!responseModal) {
        modalConfirmacion.close();
        return;
      }
      try {
        const rolesPorAsociar = Array.from(functionIdsAssoc).sort();
        const rolesDesleccionados = Array.from(functionDesc).sort();
        const responsePost = await sendData(
          "Modules/Roles/Controller/rolesController.php",
          "POST",
          "setPermisos",
          { rolesPorAsociar, rolesDesleccionados, rolId }
        );

        if (!responsePost.status) {
          initAlert(error.message, "error", toastOptions);
          modalConfirmacion.close();
        }
        modalConfirmacion.close();
        modalAsing.close();
        initAlert(responsePost.message, "success", toastOptions);
        // Esto lo hago para recargar la página y así ver los últimos cambios de la página del menú, buscar como hacerlo de una mejor manera.
        setTimeout(() => {
          location.reload();
        }, 400);
        return;
      } catch (error) {
        initAlert(error.message, "info", toastOptions);
        return;
      }
    }
  );
});
  
// Renderizar la vista de la tabla roles.
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
    btnStatus.setAttribute("data-id", `${rl.rl_id}`);
    btnStatus.setAttribute("data-status", `${rl.rl_status}`);
    btnStatus.setAttribute("class", "btnStatus");
    btnAsig.setAttribute("type", "button");
    btnAsig.setAttribute("data-rol", `${rl.rl_id}`);
    btnAsig.setAttribute("class", "btnAsig");
    let iconEditar = createI("border_color");
    let iconStatus = createI("delete_sweep");
    let iconAsig = createI("build");
    btnEditar.appendChild(iconEditar);
    btnAsig.appendChild(iconAsig);
    btnStatus.appendChild(iconStatus);
    tdActions.appendChild(btnAsig);
    tdActions.appendChild(btnEditar);
    tdActions.appendChild(btnStatus);
    addClassItem(btnAsig, {btn: "btn", waves: "waves-effect"});
    addClassItem(btnEditar, {btn: "btn",waves: "waves-effect", hoover: "waves-orange" });
    addClassItem(btnStatus, {btn: "btn", waves: "waves-effect"});
    tdID.innerText = rl.rl_id;
    tdNombre.innerText = rl.rl_nombre;
    tdDescript.innerText = rl.rl_descripcion;
    let valueStatus = rl.rl_status === "1" ? "Activo" : "Inactivo";
    tdStatus.innerText = valueStatus;
    tableBodyRoles.appendChild(trRoles);
    trRoles.append(tdID, tdNombre, tdDescript, tdStatus, tdActions);
    // Botón de editar.
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

    // Botón de inactivar
    btnStatus.addEventListener("click", (e) => {
      e.stopPropagation();
      e.preventDefault();
      const id = e.target.getAttribute("data-id");
      const status = e.target.getAttribute("data-status");
      console.log({ status, id });
      modalConfirmacion.open();
      const dataStatus = {
        idRol: id,
        statusRol: status,
      };
      let titulo = status === "1" ? "Inhabilitar rol" : "Habilitar rol";
      mostrarConfirmacion(
        titulo,
        "¿Desea continuar con el proceso?",
        async (respuesta) => {
          if (!respuesta) {
            initAlert("Proceso cancelado", "info", toastOptions);
            modalConfirmacion.close();
            return;
          }
          try {
            const response = await sendData(
              "Modules/Roles/Controller/RolesController.php",
              "PUT",
              "statusRol",
              dataStatus
            );
            initAlert(
              "Estatus del rol actualizado correctamente",
              "success",
              toastOptions
            );
            renderRoles();
          } catch (error) {
            initAlert(
              `Error al inhabilitar el rol, \n ${error.message}`,
              "error",
              toastOptions
            );
            modalConfirmacion.close();
          }
        }
      );
    });

    // Botón de asignar
    btnAsig.addEventListener("click", async (e) => {
      e.stopPropagation();
      e.preventDefault();
      let Btn = e.target;
      let permisosAsignados = null;
      let idDataRol = Btn.getAttribute("data-rol");
      // Asigno el valor a la variable global para enviarla al back.
      rolId = idDataRol;
      const response = await getPermisosRolAsig({ data: idDataRol });
      permisosAsignados = response.data.data;
      renderRolesFunciones({ rolesPermisos: permisosAsignados });
      modalAsing.open();
    });
  });
};

const mapObj = {
  modal_rol_nombre: "Nombre del Rol",
  rol_descripcion: "Descripción del Rol",
  rol_id: "ID del Rol",
};

const mapObjAdd = {
  rol_nombre: "Nombre del Rol",
  rol_descripcion: "Descripción del Rol",
};

const optionals = ["rol_descripcion"];
formEditarRol.addEventListener("submit", async (e) => {
  e.preventDefault();
  e.stopPropagation();
  const formEdit = new FormData(e.target);
  const objData = Object.fromEntries(formEdit);
  if (
    !validateFormData({
      formData: formEdit,
      campos: optionals,
      mapForm: mapObj,
    })
  )
    return;

  try {
    const updateInfo = await sendData(
      "Modules/Roles/Controller/RolesController.php",
      "PUT",
      "updateRol",
      objData
    );
    console.log(updateInfo);
    if (updateInfo.status) {
      renderRoles();
      initAlert("Recurso actualizado", "success", toastOptions);
      modalEditar.close();
    }
  } catch (error) {
    console.log(error);
    initAlert(`${error.message}`, "error", `${error}`);
  }
});

formRol.addEventListener("submit", async (e) => {
  e.preventDefault();
  e.stopPropagation();
  const formAdd = new FormData(e.target);
  const data = Object.fromEntries(formAdd);
  if (
    !validateFormData({
      formData: formAdd,
      campos: optionals,
      mapForm: mapObjAdd,
    })
  )
    return;

  try {
    const responseAdd = await sendData(
      "Modules/Roles/Controller/RolesController.php",
      "POST",
      "addRol",
      data
    );

    if (responseAdd.status) {
      initAlert(
        "Rol agregado a la base de datos con exito",
        "success",
        toastOptions
      );
      renderRoles();
      formRol.reset();
    }
  } catch (error) {
    initAlert(`${error.message}`, "error", toastOptions);
  }
});
document.addEventListener("DOMContentLoaded", () => {
  renderRoles();
  closeModal(modalAsing, closeModalBtnAsing, () => {
    rolId = null;
  });

  closeModal(modalEditar, closeModalBtnEdit);
});