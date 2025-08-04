import { initAlert, toastOptions, validateFormData } from "../utils/cases.js";

const form = document.getElementById("formCreateCategoria");

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      body: formData,
    });

    if (!response.ok) throw new Error("Respuesta del servidor no válida");

    const data = await response.json();

    if (data.success) {
      form.reset();

      initAlert(data.message, "success", {
        displayLength: 5000
      });

      setTimeout(() => {
        window.location.reload();
      }, 500);

    } else {
      initAlert(data.mensaje || "Ocurrió un error al registrar.", "error", toastOptions);
    }

  } catch (error) {
    console.error("Error:", error);
    initAlert("Error en la conexión con el servidor.", "error", toastOptions);
  }
});
