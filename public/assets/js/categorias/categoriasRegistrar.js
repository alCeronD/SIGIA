const form = document.getElementById("formCreateCategoria");
const mensajeDiv = document.getElementById("mensajeCategoria");
const tbody = document.querySelector(".tblConfigModules tbody");
const paginacionDiv = document.getElementById("paginacionCategoria");

let categorias = [];
const filasPorPagina = 5;
let paginaActual = 1;

// Inicializa categorías existentes
document.querySelectorAll(".tblConfigModules tbody tr").forEach(tr => {
  const tds = tr.querySelectorAll("td");
  if (tds.length >= 3) {
    categorias.push({
      ca_nombre: tds[0].textContent,
      ca_descripcion: tds[1].textContent,
      ca_status: tds[2].textContent === "Activo" ? 1 : 0,
      ca_id: tr.querySelector(".btnEditarCategoria")?.dataset.id || Date.now()
    });
  }
});

function renderTabla() {
  tbody.innerHTML = "";
  const inicio = (paginaActual - 1) * filasPorPagina;
  const fin = inicio + filasPorPagina;
  const categoriasPagina = categorias.slice(inicio, fin);

  categoriasPagina.forEach(categoria => {
    const nuevaFila = document.createElement("tr");
    nuevaFila.innerHTML = `
      <td>${categoria.ca_nombre}</td>
      <td>${categoria.ca_descripcion}</td>
      <td>${categoria.ca_status === 1 ? "Activo" : "Inactivo"}</td>
      <td class="accionesUsuarios">
        <a href="#"
          class="btnEditarCategoria waves-effect waves-light btn"
          data-id="${categoria.ca_id}"
          data-nombre="${categoria.ca_nombre}"
          data-descripcion="${categoria.ca_descripcion}"
          data-status="${categoria.ca_status}">
          <i class="material-icons">edit</i>
        </a>
      </td>
    `;

    nuevaFila.querySelector(".btnEditarCategoria").addEventListener("click", function (e) {
      e.preventDefault();
      document.getElementById("modal_ca_id").value = this.dataset.id;
      document.getElementById("modal_ca_nombre").value = this.dataset.nombre;
      document.getElementById("modal_ca_descripcion").value = this.dataset.descripcion;
      document.getElementById("modal_ca_status").value = this.dataset.status;
      document.getElementById("modalEditarCategoria").style.display = "flex";
    });

    tbody.appendChild(nuevaFila);
  });

  renderPaginacion();
}

function renderPaginacion() {
  paginacionDiv.innerHTML = "";
  const totalPaginas = Math.ceil(categorias.length / filasPorPagina);

  if (totalPaginas <= 1) return;

  const ul = document.createElement("ul");
  ul.className = "pagination";

  for (let i = 1; i <= totalPaginas; i++) {
    const li = document.createElement("li");
    li.className = "waves-effect";
    li.setAttribute("data-pagina", i);

    if (i === paginaActual) li.classList.add("active");

    li.innerHTML = `<a href="#!">${i}</a>`;

    li.addEventListener("click", (e) => {
      e.preventDefault();
      paginaActual = i;
      renderTabla();
    });

    ul.appendChild(li);
  }

  paginacionDiv.appendChild(ul);
}

// Envío del formulario
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      const categoria = data.categoria;

      if (!categoria || !categoria.ca_nombre) {
        mensajeDiv.textContent = "Error: respuesta incompleta.";
        return;
      }

      mensajeDiv.textContent = "Categoría registrada correctamente.";
      mensajeDiv.style.color = "green";
      form.reset();

      categorias.push(categoria);
      paginaActual = Math.ceil(categorias.length / filasPorPagina);
      renderTabla();
    } else {
      mensajeDiv.textContent = data.mensaje || "Error desconocido.";
      mensajeDiv.style.color = "red";
    }

  } catch (error) {
    console.error("Error capturado en catch:", error);
    mensajeDiv.textContent = "Error en la conexión.";
    mensajeDiv.style.color = "red";
  }
});

// Render inicial
renderTabla();
