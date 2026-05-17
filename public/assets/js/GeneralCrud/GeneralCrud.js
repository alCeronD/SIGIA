import { getData, sendData } from '../utils/index.js';

// necesito traer la data.
const url = "dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&function=selectData";
const formGeneral = document.querySelector('#formGeneral');

const render = async () => {
  const dataR = await getData(
    url,
    "GET",
    {}, false, {}
  );
}

formGeneral.addEventListener('submit', async (e) => {
  e.preventDefault();
  e.stopPropagation();

  const newFormData = new FormData(e.target);
  let dataForm = Object.fromEntries(newFormData);
  const urlForm = e.target.getAttribute('action');
  const response = await sendData(urlForm, 'POST', dataForm);
  console.log(urlForm);


});
