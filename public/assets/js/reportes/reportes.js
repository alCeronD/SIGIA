/* public/assets/js/reportes/reportes.js */
import { initAlert, toastOptions } from "../utils/cases.js";

document.addEventListener("DOMContentLoaded", () => {
  M.FormSelect.init(document.querySelectorAll("select"));

  const R = window.RUTAS_REPORTE;

  /* --- refs DOM --- */
  const selectTipo   = document.getElementById("tipoElemento");
  const selectEstado = document.getElementById("estadoElemento");

  const tablaHead = document.getElementById("tabla-previa-head");
  const tablaBody = document.getElementById("tabla-elementos-body");
  const pager     = document.getElementById("paginacion-elementos");

  const btnExcelElementos   = document.getElementById("btnDescargar");
  const btnExcelMovimientos = document.getElementById("btnDescargarTrazabilidad");

  const rbElementos   = document.getElementById("btnFiltroElementos");
  const rbMovimientos = document.getElementById("btnFiltroTrazabilidad");

  const filtroElementos = document.getElementById("filtroElementos");
  const filtroMovs      = document.getElementById("filtroTrazabilidad");

  const trzTipo        = document.getElementById("trzTipoElemento");
  const trzFechaInicio = document.getElementById("trzFechaInicio");
  const trzFechaFin    = document.getElementById("trzFechaFin");
  const btnBuscarMovs  = document.getElementById("btnBuscarTrazabilidad");

  /* --- estado tabla --- */
  let data  = [];
  let page  = 1;
  const per = 10;

  /* --- helpers de render --- */
  const header = (isMov) => `
     <tr>
       <th>#</th><th>Nombre</th><th>Placa</th>
       ${isMov ? "<th>Tipo movimiento</th>" : ""}
       <th>Existencia</th><th>${isMov ? "Fecha" : "Estado"}</th>
     </tr>`;

  const pintar = () => {
    const ini = (page - 1) * per;
    const fin = ini + per;
    tablaBody.innerHTML =
      data.slice(ini, fin).map((d) => `
        <tr>
          <td>${d.codigoElemento}</td>
          <td>${d.nombreElemento}</td>
          <td>${d.placa || "—"}</td>
          ${rbMovimientos.checked ? `<td>${d.tipoMovimiento}</td>` : ""}
          <td>${d.cantidad || 0}</td>
          <td>${rbMovimientos.checked ? d.fechaMovimiento : d.estadoElemento}</td>
        </tr>`
      ).join("") ||
      `<tr><td colspan="${rbMovimientos.checked ? 6 : 5}" class="red-text">
        No se encontraron registros
      </td></tr>`;
  };

  const paginar = () => {
    const total = Math.ceil(data.length / per);
    pager.innerHTML = Array.from({ length: total }, (_, i) => `
      <li data-pag="${i + 1}" class="waves-effect ${i + 1 === page ? "active" : ""}">
        <a href="#!">${i + 1}</a>
      </li>`).join("");

    pager.querySelectorAll("li").forEach(li => {
      li.onclick = (e) => { e.preventDefault(); page = +li.dataset.pag; pintar(); paginar(); };
    });
  };

  /* --- ajax wrapper --- */
  const fetchJSON = (url, fd) =>
    fetch(url, { method: "POST", body: fd, headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(r => { if (!r.ok) throw r.status; return r.json(); });

  /* ---------- ELEMENTOS ---------- */
  const cargarElementos = () => {
    const fd = new FormData();
    fd.append("tipoElemento",   selectTipo.value);
    fd.append("estadoElemento", selectEstado.value);

    fetchJSON(R.filtrarElementos, fd)
      .then(res => {
        rbElementos.checked = true;
        tablaHead.innerHTML = header(false);
        data = res; page = 1; pintar(); paginar();

        btnExcelElementos.href =
          `${R.reporteExcel}&tipoElemento=${encodeURIComponent(selectTipo.value)}`
          + `&estadoElemento=${encodeURIComponent(selectEstado.value)}`;
      })
      .catch(() => initAlert("Error al cargar elementos", "error", toastOptions)); // <- solicitado
  };

  /* ---------- MOVIMIENTOS ---------- */
  const cargarMovimientos = () => {
    if (!trzFechaInicio.value || !trzFechaFin.value) {
      initAlert("Favor ingresar fechas", "info", toastOptions);
      return;
    }

    const fd = new FormData();
    fd.append("tipoElemento", trzTipo.value);
    fd.append("fechaInicio",  trzFechaInicio.value);
    fd.append("fechaFin",     trzFechaFin.value);

    fetchJSON(R.filtrarTrazabilidad, fd)
      .then(res => {
        rbMovimientos.checked = true;
        tablaHead.innerHTML = header(true);
        data = res; page = 1; pintar(); paginar();

        btnExcelMovimientos.href =
          `${R.reporteTrazabilidad}&tipoElemento=${encodeURIComponent(trzTipo.value)}`
          + `&fi=${encodeURIComponent(trzFechaInicio.value)}&ff=${encodeURIComponent(trzFechaFin.value)}`;
      })
      .catch(() => initAlert("Error al cargar entradas/salidas", "error", toastOptions));
  };

  /* --- Listeners filtros --- */
  selectTipo.onchange   = () => rbElementos.checked && cargarElementos();
  selectEstado.onchange = () => rbElementos.checked && cargarElementos();
  btnBuscarMovs.onclick = cargarMovimientos;

  rbElementos.onchange = () => {
    filtroElementos.style.display = "";
    filtroMovs.style.display      = "none";
    cargarElementos();
  };

  rbMovimientos.onchange = () => {
    filtroElementos.style.display = "none";
    filtroMovs.style.display      = "";
    tablaHead.innerHTML = header(true);
    tablaBody.innerHTML = "";
    pager.innerHTML     = "";
  };

  /* ---------- Bloquea descarga sin fechas ---------- */
  btnExcelMovimientos.addEventListener("click", (e) => {
    if (!trzFechaInicio.value || !trzFechaFin.value || btnExcelMovimientos.getAttribute("href") === "#") {
      e.preventDefault();
      initAlert("Favor ingresar fechas", "info", toastOptions);
    }
  });

  /* ---------- Carga inicial ---------- */
  tablaHead.innerHTML = header(false);
  cargarElementos();
});
