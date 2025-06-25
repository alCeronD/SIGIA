import { instanceModal, options } from "../utils/cases.js";

const btnEdit = document.querySelectorAll(".btnEditarCategoria");
const closeModalBtn = document.querySelector(".close-modal");

const modalCategoria = instanceModal("#modalEditarCategoria", {
  inDuration: options.inDuration,
  outDuration: options.outDuration,
  opacity: options.opacity,
});
//A los td que tengan el estatus de data-StatusTd le coloco rojo o verde segun su estado.
let td = document.querySelectorAll("[data-statusTd]");
td.forEach((itemTd) => {
  itemTd =
    itemTd.textContent === "Activo"
      ? (itemTd.style.color = "green")
      : (itemTd.style.color = "red");
});

//Carga y visualizacion del modal
btnEdit.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    // Cargar valores
    document.getElementById("modal_ca_id").value = btn.dataset.id;
    document.getElementById("modal_ca_nombre").value = btn.dataset.nombre;
    document.getElementById("modal_ca_descripcion").value =
      btn.dataset.descripcion;
    document.getElementById("modal_ca_status").value = btn.dataset.status;
    // Mostrar modal
    
    M.FormSelect.init(document.querySelectorAll("#modal_ca_status"));
    modalCategoria.open();

    //Inicializar los inputs
    M.updateTextFields();
  });
});

document.addEventListener("DOMContentLoaded", () => {
  if (closeModalBtn && modalCategoria) {
    closeModalBtn.addEventListener("click", () => {
      // modal.style.display = "none";
      modalCategoria.close();
    });
  }
});
