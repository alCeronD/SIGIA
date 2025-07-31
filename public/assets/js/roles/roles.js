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
const closeModalBtn = document.querySelector(".closeModalBtn");
const modalEditar = instanceModal("#modalEditar", options);
const modalAsing = instanceModal("#modalAsingPermisos", options);
// Div contenedor en donde se va a renderizar toda la información.
const asigPermisosContent = document.querySelector('#asigPermisosContent');
const tableBodyRoles = document.querySelector("#tableBodyRoles");
const formEditarRol = document.querySelector("#formEditarRol");
const modalConfirmacion = instanceModal('#modalConfirmacion', options);

// Función para traer los roles y las funciones junto a su modulo.
const renderRolesFunciones = async ({rolesPermisos = []}= {})=>{
  const getRlFunciones = await getData(
    "Modules/Roles/Controller/rolesController.php",
    "GET",
    { action: "getRolesPermisos" }
  );
  const rolesYFunciones = getRlFunciones.data.data.funciones;
  const modulos = getRlFunciones.data.data.modulos;
  console.log(rolesYFunciones);
  console.log(modulos);

  asigPermisosContent.innerHTML = "";
  // Itinerar sobre los keys y la valor
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
    modulos.forEach(element => {
      // console.log(element);
      if (element.nombre_Modulo === nombreModulo) {
        const pCheckbox = createCheckboxGeneric({value :element.idModulo});
        contendorSpanModulo.appendChild(pCheckbox);
      }
    });
    
    funcionesModulo.forEach((funcion) => {
      // Contenedor de cada función con checkbox
      const contenedor = document.createElement("div");
      contenedor.classList.add("funcionNameCheckbox");

      const checkBoxNmGeneric = createCheckboxGeneric({
        text: funcion.nmFuncion,
        value: funcion.idFuncion,
      });

      // Agregar al contenedor
      contenedor.appendChild(checkBoxNmGeneric);
      // contenedor.appendChild(checkbox);

      // Agregar al contenedor general
      divFunciones.appendChild(contenedor);
    });

    // Estructura de los contenedores.
    contenedorModulo.appendChild(divFunciones);
    asigPermisosContent.appendChild(contenedorModulo);
  });
};

// Función para traer las funciones que tiene asociadas el ROL, esta función sirve para verificar que si el permiso está asociado, seleccionar el checkbox automaticamente.
const getPermisosRolAsig = async ({data = null}= {})=>{
  const responseData = await getData('Modules/Roles/Controller/RolesController.php', "GET", {action: "getPermisosRolAsig", idRol: data});
  return responseData;
}

// Vista de roles.
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
    btnAsig.setAttribute("type", "button");
    btnAsig.setAttribute("data-id", `${rl.rl_id}`);
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
    btnStatus.addEventListener("click", (e)=>{
      e.stopPropagation();
      e.preventDefault();
      const id = e.target.getAttribute('data-id');
      const status = e.target.getAttribute('data-status');
      console.log({status, id});
      modalConfirmacion.open();
      const dataStatus = {
        idRol: id, 
        statusRol: status
      };
      let titulo = status === '1'? "Inhabilitar rol": "Habilitar rol";
      mostrarConfirmacion(titulo,"¿Desea continuar con el proceso?", async (respuesta)=>{
        if (!respuesta) {
          initAlert("Proceso cancelado", "info", toastOptions);
          modalConfirmacion.close();
          return;
        }
        try {
          const response = await sendData('Modules/Roles/Controller/RolesController.php', "PUT", "statusRol", dataStatus);
          initAlert("Estatus del rol actualizado correctamente", "success", toastOptions);
          renderRoles();
        } catch (error) {
          initAlert(`Error al inhabilitar el rol, \n ${error.message}`, "error", toastOptions);
          modalConfirmacion.close();
        }
      });
    });

    // Botón de asignar
    btnAsig.addEventListener('click',async (e)=>{
      e.stopPropagation();
      e.preventDefault();
      let Btn = e.target;
      let permisosAsignados =  null;
      let idDataRol = Btn.getAttribute("data-id");
      const response =  await getPermisosRolAsig({data: idDataRol});
      permisosAsignados = response.data.data;
      renderRolesFunciones({rolesPermisos: permisosAsignados});
      modalAsing.open();
    });
  });
};

const mapObj = {
  modal_rol_nombre: "Nombre del Rol",
  rol_descripcion: "Descripción del Rol",
  rol_id: "ID del Rol"
};

const mapObjAdd = {
  rol_nombre: "Nombre del Rol",
  rol_descripcion: "Descripción del Rol"
};

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
        initAlert("Recurso actualizado", "success", toastOptions);
        modalEditar.close();
    }
  } catch (error) {
    console.log(error);
    initAlert(`${error.message}`, "error", `${error}`);
  }

});

const formRol = document.querySelector('#formRol');
formRol.addEventListener('submit', async (e)=>{
  e.preventDefault();
  e.stopPropagation();
  const formAdd = new FormData(e.target);
  const data = Object.fromEntries(formAdd);
  if(!validateFormData({formData: formAdd, campos: optionals, mapForm: mapObjAdd})) return;

  try {
    const responseAdd = await sendData('Modules/Roles/Controller/RolesController.php', 'POST', 'addRol', data);
    console.log(responseAdd);

    if (responseAdd.status) {
      initAlert("Rol agregado a la base de datos con exito","success", toastOptions );
      renderRoles();
      formRol.reset();
    }
  } catch (error) {
    initAlert(`${error.message}`, "error", toastOptions);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  renderRoles();
  // renderRolesFunciones();
  closeModal(modalEditar, closeModalBtn);
  
});