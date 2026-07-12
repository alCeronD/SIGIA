import {
  fillDataForm,
  initAlert,
  InitComponents,
  mostrarConfirmacion,
  Render,
} from '../../../../public/assets/js/utils/index.js';

// hacer el fetch de los datos personales.
const url = 'dashboard.php?modulo=Usuarios&controlador=Usuarios&function=';
const UpdatePersonalData = new Render({});
const formUpdatePersonalData = document.querySelector('#formUpdateUserView');
const textAreaPersonalData = document.querySelector('#usu_observacion');
let personalData = null;
const getPersonalData = async () => {
  personalData = await UpdatePersonalData.getData(`${url}actualizarDatosView`, 'GET');
  let rows = personalData.data;
  // renderizar la data en el formulario
  fillDataForm(rows, formUpdatePersonalData);
  InitComponents.initInputs();
  InitComponents.initTextArea(textAreaPersonalData);
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
        // console.log(personalData.data);
        // console.log(dataUpdate);

        // COMPARAR EL OBJETO DE LOS DATOS PREVIOS CON LOS NUEVOS PARA VALIDAR SI HAY QUE ENVIAR PETICION O NO.
        // extraer llaves del objeto
        let keysPersonalData = Object.keys(personalData.data);

        // const responseUpdatePersonalData = await UpdatePersonalData.sendData(
        //   url,
        //   'PUT',
        //   dataUpdate
        // );
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
