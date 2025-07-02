import { setReserva } from "../utils/cases.js";
import { getData } from "../utils/fetch.js";


document.addEventListener('DOMContentLoaded', ()=>{
    //Aca se carga todo.

});

const getElements = async (type = 'all',action = 'elements',page = 1)=>{
    const dataElements = await getData(`modules/elementos/controller/elementosController.php`,'GET',{"action":action,"pages":page, type});
    let data = dataElements.data;
    console.log(data);

}

getElements();