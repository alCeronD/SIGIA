import { Ajax } from "../libraries/ajax.js";

const formulario = document.querySelector("#formTp");
const objAjax2 = new Ajax();

let table = 'tipo_documento';

let status = 1;
const myModal = document.querySelector("#modalTp");
//Cuerpo de tabla.
const tableBody = document.querySelector("#tableBody");
//Boton de update del modal
const tpUpdateForm = document.querySelector("#tpUpdateForm");
console.log(tpUpdateForm);

let idPk;
let nombreTp;
let descripcion;

function fetchData() {
  tableBody.innerHTML = "";
  //let dataFetch = [table, status];

  objAjax2.request.open(
    "GET",
    `modules/configModules/api/apiConfigModules.php?tableName=${encodeURIComponent(
      table
    )}&status=${encodeURIComponent(status)}`
  );

  //Aca va la respuesta y el renderizado de los datos en la tabla.
  objAjax2.request.onload = () => {
    //Capturo la respuesta
    let response = JSON.parse(objAjax2.request.responseText);
    let data = response.data;
    console.log(response);

    if (objAjax2.request.status) {
      //console.log(objAjax22.request.responseText);
      if (data.length === 0) {
        const spanMessage = document.createElement("span");
        spanMessage.innerText = "Sin registros";
        tableBody.appendChild(spanMessage);
      }

      data.forEach((dta) => {
        //boton de acción
        const btnUpdate = document.createElement("button");
        const btnDelete = document.createElement("button");
        btnUpdate.setAttribute("class", "btnUpdate");
        btnUpdate.innerText = "Actualizar";
        btnDelete.setAttribute("class", "btnDelete");
        btnDelete.innerText = "Eliminar";
        const tr = document.createElement("tr");
        const tdId = document.createElement("td");
        const tdName = document.createElement("td");
        const tdDescript = document.createElement("td");
        const tdStatus = document.createElement("td");
        const tdAccion = document.createElement("td");
        // Asigno el botón a ambos elementos.

        tableBody.appendChild(tr);

        tdId.textContent = dta.tp_id;
        tdName.textContent = dta.tp_sigla;
        tdDescript.textContent = dta.tp_nombre;

        //Dependiendo del estatus, en html se verá visible activo o inactivo pero sabemos que 1 es activo y 0 inactivo.
        tdStatus.textContent = dta.tp_status === 1 ? "Activo" : "Inactivo";

        //Coloco el color rojo verde segun su estado.
        if (dta.tp_status === 1) {
          tdStatus.textContent = "Activo";
          tdStatus.style.color = "green";
        } else {
          tdStatus.textContent = "Inactivo";
          tdStatus.style.color = "red";
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
          nombreTp = celda[1].textContent;
          descripcion = celda[2].textContent;

          //Inputs del modal
          let siglaTp_documento = document.querySelector("#siglaTp_documento");
          let descripcionTp_documento = document.querySelector(
            "#descripcionTp_documento"
          );
          //Adjunto los valores al input del modal.
          siglaTp_documento.value = nombreTp;
          descripcionTp_documento.value = descripcion;

          //Abro el modal.
          myModal.style.display = "flex";
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

            objAjax2.request.open(
              "POST",
              "modules/configModules/api/apiConfigModules.php",
              true
            );
            objAjax2.request.setRequestHeader(
              "X-HTTP-Method-Override",
              "DELETE"
            );
            objAjax2.request.setRequestHeader(
              "Content-Type",
              "application/json"
            );

            objAjax2.request.onload = () => {
              let dta = JSON.parse(objAjax22.request.responseText);

              if (dta.status === false) {
                alert("Error al actualizar el registro.");
              } else {
                alert("Registro actualizado");
                fetchData(); // Refrescar tabla
              }
            };

            objAjax2.request.send(data);
          }
        });
      });
    }
  };

  //Establezco que su envio de solicitud es mediante un json.
  objAjax2.request.setRequestHeader("Accept", "application/json");
  //Enviar datos a get para visualziar las areas
  objAjax2.request.send();
}

fetchData();

tpUpdateForm.addEventListener("submit", (e) => {
  e.stopPropagation();
  e.preventDefault();

  let form = new FormData(tpUpdateForm);
  let dta = Object.fromEntries(form);

  //Guardo la pk.
  dta["tp_id"] = idPk;
  dta["tableName"] = table;

  let data = JSON.stringify(dta);
  console.log(data);

  objAjax2.request.open(
    "PUT",
    `modules/configModules/api/apiConfigModules.php?data=${encodeURIComponent(
      data
    )}`
  );
  objAjax2.request.setRequestHeader("Content-Type", "application/json");

  objAjax2.request.onload = () => {
    //Transformo en un texto la respuesta.
    let dataStatus = objAjax2.request.responseText;
    //Transformo en un json la respuesta.
    dataStatus = JSON.parse(dataStatus);
    if (dataStatus.status) {
      alert("registro actualizado");
      //Cerrar el modal
      myModal.style.display = "none";

      //Renderizo nuevamente la data.
      fetchData();
    }
  };
  objAjax2.request.send(data);
});
