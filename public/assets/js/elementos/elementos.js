document.addEventListener('DOMContentLoaded', () => {
    const elementosData = window.elementosData || [];
    const modal = document.getElementById('modalVerMas');
    const modalCerrar = document.getElementById('modalCerrar');

    // Asigna evento a cada botón "Ver Más"
    document.querySelectorAll('.btnVerMas').forEach(btn => {
        btn.addEventListener('click', () => {
            const cod = btn.getAttribute('data-cod');
            const elemento = elementosData.find(e => e.codigoElemento == cod);

            if (elemento) {
                document.getElementById('modalCod').textContent = elemento.codigoElemento;
                document.getElementById('modalPlaca').textContent = elemento.placa;
                document.getElementById('modalNombre').textContent = elemento.nombreElemento;
                document.getElementById('modalExistencia').textContent = elemento.cantidad;
                document.getElementById('modalUniMedida').textContent = elemento.unidadMedida;
                document.getElementById('modalTipo').textContent = elemento.tipoElemento;
                document.getElementById('modalEstado').textContent = elemento.estadoElemento;
                document.getElementById('modalArea').textContent = elemento.nombreArea;

                modal.classList.add('show');
            }
        });
    });

    // Cerrar modal con botón (X)
    modalCerrar.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    // Cerrar modal al hacer clic fuera del contenido
    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    //filtro elementosViews
    document.getElementById('filtroTipo').addEventListener('change', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('tbody tr');

        filas.forEach(fila => {
            const tipoFila = fila.getAttribute('data-tipo').toLowerCase();

            if (filtro === 'todos' || tipoFila === filtro) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });


    

    


const abrirModalBtn = document.getElementById('abrirModalRegistrar');
    const modalRegistrar = document.getElementById('modalRegistrar');
    const cerrarModalBtn = document.getElementById('cerrarModalRegistrar');
    const tipoElementoSelect = document.getElementById('tipoElementoSelect');
    const formDevolutivo = document.getElementById('formDevolutivo');
    const formConsumible = document.getElementById('formConsumible');

    abrirModalBtn.addEventListener('click', () => {
        modalRegistrar.style.display = 'flex';
        // Resetear selector y formularios al abrir
        tipoElementoSelect.value = '';
        formDevolutivo.style.display = 'none';
        formConsumible.style.display = 'none';
        formDevolutivo.reset();
        formConsumible.reset();
    });

    cerrarModalBtn.addEventListener('click', () => {
        modalRegistrar.style.display = 'none';
    });

    // Mostrar el formulario según el tipo seleccionado
    tipoElementoSelect.addEventListener('change', () => {
        if (tipoElementoSelect.value === 'devolutivo') {
            formDevolutivo.style.display = 'block';
            formConsumible.style.display = 'none';
        } else if (tipoElementoSelect.value === 'consumible') {
            formConsumible.style.display = 'block';
            formDevolutivo.style.display = 'none';
        } else {
            formDevolutivo.style.display = 'none';
            formConsumible.style.display = 'none';
        }
    });

    // Cerrar modal si clic fuera del contenido
    window.addEventListener('click', e => {
        if (e.target === modalRegistrar) {
            modalRegistrar.style.display = 'none';
        }
    });
});
