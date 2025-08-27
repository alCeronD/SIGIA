

// Archivo para exportar todos los submit y trabajarlos de manera global, se usa una factory function, es decir, funciones generadoras para hacer el proceso.

export function cancelarProcesoPreview(dataCodigo,modalCancel,toastOptions, sendData, initAlert, renderReservas, currentPage, valueSelect) {
  return async function cancelarProceso(f) {
    f.stopPropagation();
    f.preventDefault();
    const dataCancel = new FormData(f.target);
    let dataForm = Object.fromEntries(dataCancel.entries());
    let dataOptions = Object.values(dataForm);
    console.log(dataForm);
    console.log(dataOptions);
    if (dataForm.radioCancel === "on" && dataForm.observacion === "") {
      initAlert("El campo observación es obligatorio", "info", toastOptions);
      return;
    }

    if (dataForm.observacion === "" && dataOptions.length === 0) {
      initAlert(
        "Seleccione una opcion requerida (SI/NO)",
        "info",
        toastOptions
      );
      return;
    }

    dataForm = {
      ...dataForm,
      codigoPrestamo: dataCodigo,
    };

    delete dataForm.radioCancel;
    console.log(dataForm);

    const response = await sendData(
      "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
      "POST",
      "cancelPrestamo",
      dataForm
    );

    if (!response.status) {
      initAlert(response.message, "error", toastOptions);
      return;
    }
    modalCancel.close();

    // Esto esta repedido, se puede transformar en función a parte.
    // Limpiar radios
    document
      .querySelectorAll('#modalCancel input[name="radioCancel"]')
      .forEach((radio) => {
        radio.checked = false;
      });

    // Limpiar textarea y deshabilitar
    const observacion = document.querySelector("#inputObservacionCancel");
    observacion.value = "";
    observacion.readOnly = true;

    initAlert(response.message, "success", toastOptions);
    renderReservas({ page: currentPage, type: valueSelect });
  };
}
