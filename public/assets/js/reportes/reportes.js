import { initAlert, toastOptions } from "../utils/cases.js";

document.addEventListener("DOMContentLoaded", () => {
  
  M.FormSelect.init(document.querySelectorAll("select"));
  const R = window.RUTAS_REPORTE;
  console.log(R);

  const rutesReportes = {
    filtrarElementos: "modules/reportes/controller/reportesController.php",
  };

  /* --------- REFERENCIAS DOM --------- */
  const selectTipo    = document.getElementById("tipoElemento");
  const selectEstado  = document.getElementById("estadoElemento");

  const tablaHead     = document.getElementById("tabla-previa-head");
  const tablaBody     = document.getElementById("tabla-elementos-body");
  const pager         = document.getElementById("paginacion-elementos");

  const btnExcelElem  = document.getElementById("btnDescargar");
  const btnExcelMov   = document.getElementById("btnDescargarTrazabilidad");
  const btnExcelPlaca = document.getElementById("btnDescargarMovimientoPlaca");

  const rbElem   = document.getElementById("btnFiltroElementos");
  const rbMov    = document.getElementById("btnFiltroTrazabilidad");
  const rbPlaca  = document.getElementById("btnFiltroElementoMovimiento");

  const filtroElem  = document.getElementById("filtroElementos");
  const filtroMov   = document.getElementById("filtroTrazabilidad");
  const filtroPlaca = document.getElementById("filtroMovimientoElemento");

  const trzTipo        = document.getElementById("trzTipoElemento");
  const trzFechaInicio = document.getElementById("trzFechaInicio");
  const trzFechaFin    = document.getElementById("trzFechaFin");
  const btnBuscarMov   = document.getElementById("btnBuscarTrazabilidad");

  const inputPlaca  = document.getElementById("placaElemento");
  const btnBuscarPl = document.getElementById("btnBuscarPorPlaca");

  /* --------- ESTADO --------- */
  let data = [];
  let page = 1;
  const per = 10;

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
      data.slice(ini, fin).map(d => `
        <tr>
          <td>${d.codigoElemento}</td>
          <td>${d.nombreElemento}</td>
          <td>${d.placa || "—"}</td>
          ${ (rbMov.checked || rbPlaca.checked) ? `<td>${d.tipoMovimiento}</td>` : "" }
          <td>${d.cantidad || 0}</td>
          <td>${ (rbMov.checked || rbPlaca.checked) ? d.fechaMovimiento : d.estadoElemento}</td>
        </tr>`).join("") ||
      `<tr><td colspan="${(rbMov.checked || rbPlaca.checked) ? 6 : 5}" class="red-text">No se encontraron registros</td></tr>`;
  };

  const paginar = () => {
    const total = Math.ceil(data.length / per);
    pager.innerHTML = Array.from({ length: total }, (_, i) => `
      <li data-pag="${i + 1}" class="waves-effect ${i + 1 === page ? "active" : ""}">
        <a href="#!">${i + 1}</a>
      </li>`).join("");

    pager.querySelectorAll("li").forEach(li => {
      li.onclick = e => { e.preventDefault(); page = +li.dataset.pag; pintar(); paginar(); };
    });
  };

  // const fetchJSON = (url, fd) =>
  //   fetch(url, {
  //     method: "POST",
  //     body: fd,
  //     headers: { "X-Requested-With": "XMLHttpRequest" },
  //   }).then((r) => {
  //     console.log(r);
  //     if (!r.ok) throw r.status;
  //     return r.json();
  //   });

  /* --------- CARGAS --------- */
  const cargarElementos = () => {
    const fd = new FormData();
    fd.append("tipoElemento", selectTipo.value);
    fd.append("estadoElemento", selectEstado.value);

    fetchJSON(R.filtrarElementos, fd)
      .then(res => {
        rbElem.checked = true;
        tablaHead.innerHTML = header(false);
        data = res; page = 1; pintar(); paginar();
        btnExcelElem.href =
          `${R.reporteExcel}&tipoElemento=${encodeURIComponent(selectTipo.value)}&estadoElemento=${encodeURIComponent(selectEstado.value)}`;

      })
      .catch(() => initAlert("Error al cargar elementos", "error", toastOptions));
  };


  const fetchJSON = (url, fd) =>
  fetch(url, {
    method: "POST",
    body: fd,
    headers: {
      "X-Requested-With": "XMLHttpRequest"
    }
  })
  .then(r => {
    console.log(" Status:", r.status);
    return r.text(); // <-- cambia temporalmente a text para ver la respuesta real
  })
  .then(txt => {
    console.log(" Respuesta cruda:", txt);
    try {
      return JSON.parse(txt); // intenta convertir
    } catch {
      throw new Error("Respuesta no es JSON");
    }
  });




  const cargarMovimientos = () => {
    if (!trzFechaInicio.value || !trzFechaFin.value) {
      initAlert("Favor ingresar fechas", "info", toastOptions); return;
    }
    const fd = new FormData();
    fd.append("tipoElemento", trzTipo.value);
    fd.append("fechaInicio", trzFechaInicio.value);
    fd.append("fechaFin", trzFechaFin.value);

    fetchJSON(R.filtrarTrazabilidad, fd)
      .then(res => {
        rbMov.checked = true;
        tablaHead.innerHTML = header(true);
        data = res; page = 1; pintar(); paginar();
        btnExcelMov.href =
          `${R.reporteTrazabilidad}&tipoElemento=${encodeURIComponent(trzTipo.value)}&fi=${trzFechaInicio.value}&ff=${trzFechaFin.value}`;
      })
      .catch(() => initAlert("Error al cargar entradas/salidas", "error", toastOptions));
  };

  const cargarMovPorPlaca = () => {
    const placa = inputPlaca.value.trim();
    if (!placa) { initAlert("Ingrese un número de placa", "info", toastOptions); return; }

    const fd = new FormData();
    fd.append("placa", placa);

    fetchJSON(R.filtrarPorPlaca, fd)
      .then(res => {
        rbPlaca.checked = true;
        tablaHead.innerHTML = header(true);
        data = res; page = 1; pintar(); paginar();
      })
      .catch(() => initAlert("Error al buscar por placa", "error", toastOptions));
  };

  /* --------- EVENTOS --------- */
  selectTipo.onchange   = () => rbElem.checked && cargarElementos();
  selectEstado.onchange = () => rbElem.checked && cargarElementos();
  btnBuscarMov.onclick  = cargarMovimientos;
  btnBuscarPl.onclick   = cargarMovPorPlaca;

  rbElem.onchange = () => {
    filtroElem.style.display = "";
    filtroMov.style.display  = "none";
    filtroPlaca.style.display = "none";
    cargarElementos();
  };
  rbMov.onchange = () => {
    filtroElem.style.display = "none";
    filtroMov.style.display  = "";
    filtroPlaca.style.display = "none";
    tablaHead.innerHTML = header(true);
    tablaBody.innerHTML = ""; pager.innerHTML = "";
  };
  rbPlaca.onchange = () => {
    filtroElem.style.display = "none";
    filtroMov.style.display  = "none";
    filtroPlaca.style.display = "";
    tablaHead.innerHTML = header(true);
    tablaBody.innerHTML = ""; pager.innerHTML = "";
  };

  /* --- bloquea descarga si faltan fechas --- */
  btnExcelMov.addEventListener("click", e => {
    if (!trzFechaInicio.value || !trzFechaFin.value) {
      e.preventDefault(); initAlert("Favor ingresar fechas", "info", toastOptions);
    }
  });

  /* --- descarga por placa --- */
/* --- descarga por placa --- */
btnExcelPlaca.addEventListener("click", e => {
  const placa = inputPlaca.value.trim();
  if (!placa) {
    e.preventDefault();               // sólo si no hay placa
    initAlert("Ingrese un número de placa para descargar", "info", toastOptions);
    return;
  }
  btnExcelPlaca.href = `${R.reporteMovimientoPlaca}?placaElemento=${encodeURIComponent(placa)}`;
});

  btnExcelPlaca.addEventListener("click", e => {
    const placa = inputPlaca.value.trim();
    if (!placa) {
      e.preventDefault();
      initAlert("Ingrese un número de placa para descargar", "info", toastOptions);
      return;
    }
  
    btnExcelPlaca.href =
      `${R.reporteMovimientoPlaca}&placaElemento=${encodeURIComponent(placa)}`;
  
  });

  /* --------- CARGA INICIAL --------- */
  tablaHead.innerHTML = header(false);
  cargarElementos();
});
