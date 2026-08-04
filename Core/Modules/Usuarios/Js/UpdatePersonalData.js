import {
  fillDataForm,
  initAlert,
  InitComponents,
  mostrarConfirmacion,
  Render,
} from '../../../../public/assets/js/utils/index.js';
import { compareObjects } from './Functions-updatePersonalData.js';

// hacer el fetch de los datos personales.
const url = 'dashboard.php?modulo=Usuarios&controlador=Usuarios&function=';
const UpdatePersonalData = new Render({});
const formUpdatePersonalData = document.querySelector('#formUpdateUserView');
const textAreaPersonalData = document.querySelector('#usu_observacion');
let personalData = null;
const getPersonalData = async () => {
  personalData = await UpdatePersonalData.getData(`${url}actualizarPersonalData`, 'GET');
  let rows = personalData.data;
  // renderizar la data en el formulario
  fillDataForm(rows, formUpdatePersonalData);
  InitComponents.initInputs();
};

// evento submit
formUpdatePersonalData.addEventListener('submit', (f) => {
  f.preventDefault();
  f.stopPropagation();
  let formData = new FormData(f.target);
  let url = f.target.dataset.url; //url en donde vamos a llevar la informacion
  let dataUpdate = Object.fromEntries(formData);
  mostrarConfirmacion(
    'Actualizar datos personales',
    '¿Está seguro de actualizar los datos?',
    async (response) => {
      try {
        if (!response) return;

        const resultCompare = compareObjects(dataUpdate, personalData.data, dataUpdate);
        // dependiendo del resultado de la funcion le enviamos al usuario que se actualizaron los datos, PESE A QUE EN REALIDAD NUNCA SE ENVIO LA PETICION porque los datos a enviar y los ya cargados son los mismos.
        if (resultCompare) {
          initAlert('Datos actualizados con exito', 'info');
          return;
        }

        const responseUpdatePersonalData = await UpdatePersonalData.sendData(
          url,
          'PUT',
          dataUpdate
        );

        if (responseUpdatePersonalData.status) {
          initAlert(responseUpdatePersonalData.message, 'success');
          formUpdatePersonalData.reset(); //reiniciar el usuario
          // enviar la peticion nuevamente.
          getPersonalData();
        }
      } catch (error) {
        initAlert(error.message, 'info');
        return;
      }
    }
  );
});

document.addEventListener('DOMContentLoaded', () => {
  getPersonalData();
});
