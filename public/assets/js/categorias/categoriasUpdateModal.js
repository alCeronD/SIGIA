//Carga y visualizacion del modal
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btnEditarCategoria").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      // Cargar valores
      document.getElementById("modal_ca_id").value = btn.dataset.id;
      document.getElementById("modal_ca_nombre").value = btn.dataset.nombre;
      document.getElementById("modal_ca_descripcion").value = btn.dataset.descripcion;
      document.getElementById("modal_ca_status").value = btn.dataset.status;
      // Mostrar modal
      document.getElementById("modalEditarCategoria").style.display = "flex";
    });
  });
});

function cerrarModal() {
  document.getElementById("modalEditarCategoria").style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {
  const closeModalBtn = document.querySelector(".close-modal");
  const modal = document.getElementById("modalEditarCategoria");

  if (closeModalBtn && modal) {
    closeModalBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }
});
