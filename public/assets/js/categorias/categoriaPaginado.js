
document.addEventListener("DOMContentLoaded", () => {
  const paginacion = document.getElementById("paginacion-categorias");
  const cuerpoTabla = document.getElementById("tbodyCategorias");
  const filas = Array.from(cuerpoTabla.querySelectorAll("tr"));
  let filasFiltradas = [...filas];
  const itemsPorPagina = 4;

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    filas.forEach(fila => fila.style.display = "none");

    filasFiltradas.forEach((fila, index) => {
      fila.style.display = (index >= inicio && index < fin) ? "table-row" : "none";
    });

    document.querySelectorAll("#paginacion-categorias li").forEach(li => li.classList.remove("active"));
    const liActivo = document.querySelector(`#paginacion-categorias li[data-pagina="${pagina}"]`);
    if (liActivo) liActivo.classList.add("active");
  }

  function generarPaginacion() {
    if (!paginacion) return;

    paginacion.innerHTML = "";
    const totalPaginas = Math.ceil(filasFiltradas.length / itemsPorPagina);

    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement("li");
      li.classList.add("waves-effect");
      li.setAttribute("data-pagina", i);
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener("click", (e) => {
        e.preventDefault();
        mostrarPagina(i);
      });
      paginacion.appendChild(li);
    }

    if (totalPaginas > 0) {
      mostrarPagina(1);
    }
  }

  generarPaginacion();
});
