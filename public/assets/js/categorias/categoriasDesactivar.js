import { initAlert, toastOptions } from '../utils/cases.js';

document.querySelectorAll(".toggleEstadoBtn").forEach(button => {
  button.addEventListener("click", async () => {
    const id = button.dataset.id;
    const estadoActual = button.dataset.status;
    const nuevoEstado = estadoActual === "1" ? "0" : "1";

    const confirmacion = confirm(`¿Estás seguro que deseas ${nuevoEstado === "0" ? "inhabilitar" : "activar"} esta categoría?`);
    if (!confirmacion) return;

    try {
      const payload = {
        action: "cambiarEstado",
        ca_id: id,
        ca_status: nuevoEstado
      };

      const response = await fetch("modules/categorias/controller/categoriasController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });

      const rawText = await response.text();
      let result;

      try {
          result = JSON.parse(rawText);
        } catch (e) {
            console.error("Respuesta no válida JSON:", rawText);
            initAlert("Respuesta inválida del servidor", "error", toastOptions);
            return;
        }
      if (result.success) {
        initAlert(result.mensaje, "success", toastOptions);
        setTimeout(() => location.reload(), 600); 
      } else {
        initAlert(result.mensaje || "Error al cambiar el estado", "error", toastOptions);
      }

    } catch (error) {
      console.error("Error en solicitud:", error);
      initAlert("Error en la solicitud", "error", toastOptions);
    }
  });
});
