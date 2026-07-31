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

export function finalizarPresvamoPreview (endReserva,sendData,modalSalida,initAlert,toastOptions,renderReservas,valueSelect,inputObservacionsalida, radioButtonSalida){
  return async function finalizarPrestamo(f) {
    f.stopPropagation();
    f.preventDefault();
    let dataFormSalida = {};
    let formData = new FormData(f.target);
    dataFormSalida = Object.fromEntries(formData.entries());

    if (Object.keys(dataFormSalida).length === 0) {
      initAlert(
        "Seleccione Si o No antes del finalizar el prestamo.",
        "info",
        toastOptions
      );
      return;
    }
    if (
      Object.values(dataFormSalida).includes("on") &&
      !dataFormSalida.observacion.trim()
    ) {
      initAlert("Campo de observación obligatorio", "info", toastOptions);
      return;
    }

    endReserva = {
      ...endReserva,
      observacionSalida: dataFormSalida.observacion,
    };

    try {
      const responseSalida = await sendData(
        "Modules/reservaPrestamos/controller/reservaPrestamosController.php",
        "POST",
        "finalizar",
        endReserva
      );
      if (responseSalida.status) {
        modalSalida.close();
        initAlert(
          `Prestamo # ${endReserva.codigoReserva} finalizada`,
          "success",
          toastOptions
        );

        // Lo ideal es renderizar esto en la página actual a la cual se encuentran los prestamos, no re direccionar a la página 1.
        renderReservas({ page: 1, type: valueSelect });

        // Limpio campo de texto y reestablezco los input radio, lo puedo implementar como función.
        inputObservacionsalida.innerText = "";
        radioButtonSalida.forEach((rd) => {
          rd.checked = false;
        });

        let codigoAdd = endReserva.codigoReserva;
        let tr = [...document.querySelectorAll("#tbodyReservaConsult tr")];
      } else {
        initAlert(
          `Respuesta del servidor: \n ${response.message}`,
          "warning",
          toastOptions
        );
        console.warn("Respuesta negativa del servidor:", response.message);
      }
    } catch (error) {}
  };
}