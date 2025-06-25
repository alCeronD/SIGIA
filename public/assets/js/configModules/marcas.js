import { Ajax } from "../utils/ajax.js";
//Cases no está dentro de las llaves xq estoy importando otras cosas por defecto.
import { closeModal, createI, instanceModal, openModal, options } from "../utils/cases.js";

const tableBody = document.querySelector("#marcaTblBody");
const formMarca = document.querySelector("#marcaForm");
const myModal = document.querySelector("#modalMarca");
const marcaUpdateForm = document.querySelector("#marcaUpdateForm");
const closeModalBtn = document.querySelector(".closeModalBtn");

const instanModal = instanceModal('#modalMarca',{"inDuration":options.inDuration, "outDuration": options.outDuration, "opacity": options.opacity });

let table = "marcas";
let status = 1;
const objAjax = new Ajax();
let nombreMarca;
let descripcionMarca;
let idPk;

function fetchData() {
 tableBody.innerHTML = "";
  objAjax.request.open(
    "GET",
    `modules/configModules/api/apiConfigModules.php?tableName=${encodeURIComponent(
      table
    )}&status=${encodeURIComponent(status)}`
  );

  //Aca va la respuesta y el renderizado de los datos en la tabla.
  objAjax.request.onload = () => {
    //Capturo la respuesta
    let response = JSON.parse(objAjax.request.responseText);
    let data = response.data;

    if (data.length === 0) {
      const spanMessage = document.createElement("span");
      spanMessage.innerText = "Sin registros";
      tableBody.appendChild(spanMessage);
    }
    data.forEach((dta) => {
      //boton de acción
      const iSave = createI();
      const iDelete = createI();

      const btnUpdate = document.createElement("button");
      const btnDelete = document.createElement("button");
      btnUpdate.setAttribute("class", "btnUpdate");
      btnDelete.setAttribute("class", "btnDelete");
      btnUpdate.append(iSave);
      const tr = document.createElement("tr");
      const tdId = document.createElement("td");
      const tdName = document.createElement("td");
      const tdDescript = document.createElement("td");
      const tdStatus = document.createElement("td");
      const tdAccion = document.createElement("td");

      btnUpdate.setAttribute('class','waves-effect waves-light btn-small light-blue');
      btnDelete.setAttribute('class','btn waves-effect waves-light btn-small modal-trigger red');
      iSave.innerText = 'edit';
      btnDelete.append(iDelete);
      tableBody.appendChild(tr);

      tdId.textContent = dta.ma_id;
      tdName.textContent = dta.ma_nombre;
      tdDescript.textContent = dta.ma_descripcion;

      //Dependiendo del estatus, en html se verá visible activo o inactivo pero sabemos que 1 es activo y 0 inactivo.
      tdStatus.textContent = dta.ma_status === 1 ? "Activo" : "Inactivo";

      //Coloco el color rojo verde segun su estado.
      if (dta.ma_status === 1) {
        tdStatus.textContent = "Activo";
        tdStatus.style.color = "green";
        iDelete.innerText = 'delete';
      } else {
        tdStatus.textContent = "Inactivo";
        tdStatus.style.color = "red";
        iDelete.innerText = 'loop';
      }

      tdAccion.append(btnUpdate, btnDelete);
      tr.appendChild(tdId);
      tr.appendChild(tdName);
      tr.appendChild(tdDescript);
      tr.appendChild(tdStatus);
      tr.appendChild(tdAccion);

      //Update Event
      btnUpdate.addEventListener("click", (f) => {
        //Guardo el botón
        let btndl = f.target;
        let row = btndl.closest("tr");
        const celda = row.querySelectorAll("td");
        
        //Capturo la información y la separo.
        idPk = celda[0].textContent;
        nombreMarca = celda[1].textContent;
        descripcionMarca = celda[2].textContent;
        //Inputs del modal
        let nombreAreaUpdate = document.querySelector("#nombreMarcaUpdate");
        let descripcionAreaUpdate = document.querySelector(
          "#descripcionMarcaUpdate"
        );
        //Adjunto los valores al input del modal.
        nombreAreaUpdate.value = nombreMarca;
        descripcionAreaUpdate.value = descripcionMarca;

        //Re inicializo los inputs de materialize ya que han sido modificados.
        M.updateTextFields();

        instanModal.open();

      });

      //Delete Event
      btnDelete.addEventListener("click", (e) => {
        e.stopPropagation();
        e.preventDefault();

        let btnDlt = e.target;
        let row = btnDlt.closest("tr");
        const celda = row.querySelectorAll("td");

        //Extraigo la información del query selector y guardo en variables.
        let idPk = celda[0].textContent;
        let status = celda[3].textContent;
        //Dependiendo del texto en html defino si es 0 para inactivo o 1 para activo para enviar a backend para actualizar.
        status = status === "Activo" ? 1 : 0;

        if (confirm("¿Está seguro de inhabilitar este elemento?")) {
          const data = JSON.stringify({
            idPk: idPk,
            status: status,
            tableName: table,
          });

          objAjax.request.open(
            "POST",
            "modules/configModules/api/apiConfigModules.php",
            true
          );
          //Mando por post pero sobreescribo el método a delete.
          objAjax.request.setRequestHeader("X-HTTP-Method-Override", "DELETE");
          objAjax.request.setRequestHeader("Content-Type", "application/json");

          objAjax.request.onload = () => {
            let dta = JSON.parse(objAjax.request.responseText);

            if (dta.status === false) {
              alert("Error al actualizar el registro.");
            } else {
              alert("Registro actualizado");
              fetchData(); // Refrescar tabla
              myModal.style.display = 'none';
            }
          };

          objAjax.request.send(data);
        }
      });
    });
  };

  //Establezco que su envio de solicitud es mediante un json.
  objAjax.request.setRequestHeader("Accept", "application/json");
  //Enviar datos a get para visualziar las areas
  objAjax.request.send();
}

document.addEventListener('DOMContentLoaded', ()=>{
  fetchData();

});

//Formulario de registro.
formMarca.addEventListener("submit", (f) => {
  f.stopPropagation();
  f.preventDefault();
  let form = new FormData(formMarca);
  let dta = Object.fromEntries(form);
  let data = JSON.stringify({
    ma_nombre: dta.ma_nombre,
    ma_descripcion: dta.ma_descripcion,
    tableName: table,
  });

  objAjax.request.open(
    "POST",
    "modules/configModules/api/apiConfigModules.php",
    true
  );
  objAjax.request.setRequestHeader("Content-Type", "application/json");
  objAjax.request.setRequestHeader("Accept", "application/json");
  objAjax.request.onload = () => {
    let response = JSON.parse(objAjax.request.responseText);
    if (response.status) {
      alert(response.message);

      formMarca.reset();
      fetchData();
    } else {
      alert(response.message);
    }
  };

  objAjax.request.send(data);
});

//Formulario de actualización
marcaUpdateForm.addEventListener("submit", (e) => {
  e.preventDefault();
  e.stopPropagation();

  let form = new FormData(marcaUpdateForm);
  let dta = Object.fromEntries(form);

  dta["ma_id"] = idPk;
  dta["tableName"] = table;

  let data = JSON.stringify(dta);

  objAjax.request.open(
    "PUT",
    `modules/configModules/api/apiConfigModules.php?data=${encodeURIComponent(
      data
    )}`
  );
  objAjax.request.setRequestHeader("Content-Type", "application/json");

  objAjax.request.onload = () => {
    let response = objAjax.request.responseText;
    let dataResponse = JSON.parse(response);

    if (dataResponse.status) {
      alert("registro actualizado.");
      fetchData();
      //Si requiero cerrar el modal de manera automatica, debo de usar el método close, propio de materialize. En caso de que requiera presionar el botón, uso la función que se creó.
      instanModal.close();
    }
  };

  objAjax.request.send(data);
});

//Cerrar modal solo con el boton de span.
closeModal(instanModal,closeModalBtn);
