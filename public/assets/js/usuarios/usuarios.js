document.addEventListener("DOMContentLoaded", () => {
  // Botones Editar
  document.querySelectorAll(".btnEditarUsuario").forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      document.getElementById("usu_id").value = btn.dataset.id;
      document.getElementById("usu_docum").value = btn.dataset.documento;
      document.getElementById("usu_nombres").value = btn.dataset.nombres;
      document.getElementById("usu_apellidos").value = btn.dataset.apellidos;
      document.getElementById("usu_email").value = btn.dataset.email;
      document.getElementById("usu_telefono").value = btn.dataset.telefono;
      document.getElementById("modalEditarUsuario").style.display = "flex";
    });
  });

  // Paginación
  const filas = Array.from(document.querySelectorAll('#tableConfig tbody tr'));
  const paginacion = document.getElementById('paginacion-usuarios');
  const itemsPorPagina = 5;
  const totalPaginas = Math.ceil(filas.length / itemsPorPagina);

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * itemsPorPagina;
    const fin = inicio + itemsPorPagina;

    filas.forEach((fila, index) => {
      fila.style.display = index >= inicio && index < fin ? 'table-row' : 'none';
    });

    // Marcar página activa
    document.querySelectorAll('#paginacion-usuarios li').forEach(li => li.classList.remove('active'));
    const liActivo = document.querySelector(`#paginacion-usuarios li[data-pagina="${pagina}"]`);
    if (liActivo) liActivo.classList.add('active');
  }

  function generarPaginacion() {
    paginacion.innerHTML = '';
    for (let i = 1; i <= totalPaginas; i++) {
      const li = document.createElement('li');
      li.classList.add('waves-effect');
      li.setAttribute('data-pagina', i);
      li.innerHTML = `<a href="#!">${i}</a>`;
      li.addEventListener('click', (e) => {
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

function cerrarModalUsuario() {
  document.getElementById("modalEditarUsuario").style.display = "none";
}