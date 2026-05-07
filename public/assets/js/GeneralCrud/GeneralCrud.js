import { getData, sendData } from '../utils/index.js';

// necesito traer la data.
const url = "dashboard.php?modulo=GeneralCrud&controlador=GeneralCrud&funcion=selectData";

const render = async () => {
  const dataR = await getData(
    url,
    "GET",
    {}, false, {}
  );
}

render();