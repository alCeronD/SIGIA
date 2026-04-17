import { initAlert, mostrarConfirmacion, toastOptions } from "./utils/cases.js";
import { sendData } from "./utils/fetch.js";
import { Storage } from "./utils/Storage.js";
const btnCerrarSesion = document.querySelector('#btnCerrarSesion');

document.addEventListener('DOMContentLoaded', () => {
  const elemsModals = document.querySelectorAll('.modal');
  M.Modal.init(elemsModals);
});

btnCerrarSesion.addEventListener("click", (e) => {
  e.stopPropagation();
  e.preventDefault();

  mostrarConfirmacion(
    "Cerrar sesión",
    "¿Deseas salir de la aplicación?",
    async (r) => {
      if (!r) {
        initAlert("Proceso cancelado", "info", toastOptions);
        return;
      }
      try {
        const data = e.target.getAttribute("data-logOut");

        const response = await sendData(data, "POST");
        console.log(response);
        if (response.status) {
          Storage.addValue({ key: "sessionStatus", item: 'false' });
          window.location.href = response.redirect;
        }
      } catch (error) {
        console.log(error);
      }
    }
  );
});