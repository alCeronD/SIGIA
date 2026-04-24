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

        const url = e.target.getAttribute("data-Url");
        let dta = e.target.getAttribute("data-logOut");
        let data = {
          action: dta
        }
        const response = await sendData(url, "POST", data);

        console.log(response);
        if (response.status) {
          console.log("Sesión cerrada");
          Storage.addValue({ key: "sessionStatus", item: 'false' });
          window.location.href = response.redirect;
        }
      } catch (error) {
        console.log(error);
      }
    }
  );
});