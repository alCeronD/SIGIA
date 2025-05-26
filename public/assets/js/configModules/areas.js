import { Ajax } from "../libraries/ajax.js";
//La idea es capturar la información aca desde javascript y enviarla al controlador.

//Formulario
const formulario = document.querySelector("#formArea");
const objAjax = new Ajax();
//Nombre de la tabla.
let table = "areas";

//Estatus del registro, si está activo o no. 1= activo, 0 =inactivo
let status = 1;

//Modal
const myModal = document.querySelector("#modalArea");
//Cuerpo de tabla.
const tableBody = document.querySelector("#tableBody");
//Boton de update del modal
const areaUpdateForm = document.querySelector("#areaUpdateForm");

let idPk;
let nombreArea;
let descripcion;

function fetchData() {
  tableBody.innerHTML = "";
  let dataFetch = [table, status];

  objAjax.request.open(
    "GET",
    `modules/configModules/areas/api/apiConfigModules.php?tableName=${encodeURIComponent(
      table
    )}&status=${encodeURIComponent(status)}`
  );

  //Aca va la respuesta y el renderizado de los datos en la tabla.
  objAjax.request.onload = () => {
    //Capturo la respuesta
    let response = JSON.parse(objAjax.request.responseText);
    let data = response.data;

    if (objAjax.request.status) {
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

        tdId.textContent = dta.ar_cod;
        tdName.textContent = dta.ar_nombre;
        tdDescript.textContent = dta.ar_descripcion;

        //Dependiendo del estatus, en html se verá visible activo o inactivo pero sabemos que 1 es activo y 0 inactivo.
        tdStatus.textContent = dta.ar_status === 1 ? "Activo" : "Inactivo";

        //Coloco el color rojo verde segun su estado.
        if (dta.ar_status === 1) {
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
          nombreArea = celda[1].textContent;
          descripcion = celda[2].textContent;

          //Inputs del modal
          let nombreAreaUpdate = document.querySelector("#nombreAreaUpdate");
          let descripcionAreaUpdate = document.querySelector(
            "#descripcionAreaUpdate"
          );
          //Adjunto los valores al input del modal.
          nombreAreaUpdate.value = nombreArea;
          descripcionAreaUpdate.value = descripcion;

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

          //let nombreArea = celda[1].textContent;
          //let descripcion = celda[2].textContent;

          if (confirm("¿Está seguro de inhabilitar este elemento?")) {
            const data = JSON.stringify({
              idPk: idPk,
              status: status,
              tableName: table,
            });

            objAjax.request.open(
              "POST",
              "modules/configModules/areas/api/apiConfigModules.php",
              true
            );
            objAjax.request.setRequestHeader(
              "X-HTTP-Method-Override",
              "DELETE"
            );
            objAjax.request.setRequestHeader(
              "Content-Type",
              "application/json"
            );

            objAjax.request.onload = () => {
              let dta = JSON.parse(objAjax.request.responseText);

              if (dta.status === false) {
                alert("Error al actualizar el registro.");
              } else {
                alert("Registro actualizado");
                fetchData(); // Refrescar tabla
              }
            };

            objAjax.request.send(data);
          }
        });
      });
    }
  };

  //Establezco que su envio de solicitud es mediante un json.
  objAjax.request.setRequestHeader("Accept", "application/json");
  //Enviar datos a get para visualziar las areas
  objAjax.request.send();
}

// Apenas cargue la información, ejecutar la función fetchData para traer la info usando ajax.
document.addEventListener("DOMContentLoaded", () => {
  //Apenas cargue el documeento me renderiza la información.
  fetchData();
});



//Enviar datos al formulario.
formulario.addEventListener("submit", (event) => {
  event.preventDefault();
  event.stopPropagation();

  let form = new FormData(formulario);
  let dt = Object.fromEntries(form);
  console.log(dt);

  let data = JSON.stringify({
    ar_nombre: dt.ar_nombre,
    ar_descripcion: dt.ar_descripcion,
    tableName: table
  });

  //Ajax Post.
  objAjax.request.open('POST',"modules/configModules/areas/api/apiConfigModules.php",true);

  // objAjax.request.setRequestHeader(
  //             "X-HTTP-Method-Override",
  //             "POST"
  //           );
  objAjax.request.setRequestHeader("Content-Type",
              "application/json");

  objAjax.request.onload = ()=>{
    /**
     * 1. traer la respuesta en jsontypetext
     * 2. validar el estatus en true or false
     * 3. adicionar el elemento en la tabla.
     * 
     */

    let data = objAjax.request.responseText;
    data = JSON.parse(data);
    console.log(data);


  }
  objAjax.request.setRequestHeader("Accept", "application/json");
  objAjax.request.send(data);
});

//Update del formulario
areaUpdateForm.addEventListener("submit", (e) => {
  e.stopPropagation();
  e.preventDefault();

  let form = new FormData(areaUpdateForm);
  let dta = Object.fromEntries(form);

  //Guardo la pk.
  dta["ar_cod"] = idPk;
  dta["tableName"] = table;

  let data = JSON.stringify(dta);

  objAjax.request.open(
    "PUT",
    `modules/configModules/areas/api/apiConfigModules.php?data=${encodeURIComponent(
      data
    )}`
  );
  objAjax.request.setRequestHeader("Content-Type", "application/json");

  objAjax.request.onload = () => {
    //Transformo en un texto la respuesta.
    let dataStatus = objAjax.request.responseText;
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
  objAjax.request.send(data);
});
