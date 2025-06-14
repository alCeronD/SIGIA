document.getElementById('filtro_area').addEventListener('change', function () {
    const selectedArea = this.value;
    const filas = document.querySelectorAll('#tabla-elementos-devolutivos tr');

    filas.forEach(fila => {
      const area = fila.getAttribute('data-area');
      if (selectedArea === "" || area === selectedArea) {
        fila.style.display = "";
      } else {
        fila.style.display = "none";
      }
    });
  });
