import { getData } from "../utils/fetch.js";
import {renderElements } from "./fetchElements.js";
const typeElements = {
    dev: 'devolutivo',
    consu: 'consumible',
    all: 'all'
}
const filtroTipo = document.querySelector('#filtroTipo');
const previewElements = document.querySelector('#previewElements');
const nextElements = document.querySelector('#nextElements');
document.addEventListener('DOMContentLoaded', ()=>{

    renderElements();
});

//La forma en como filtro los elementos se puede cambiar.
filtroTipo.addEventListener('change', (e)=>{
    e.stopPropagation();
    e.preventDefault();

    //Hacer una validación de que el valor de optión exista.
    /**
     * por ejemplo, si el optin es devolutivo o cnsumible o todo, debe ejecutar, pero si hay OTRO, debe de mostrar x defecto el todo.
     */
    const selectedOption = e.target.options[e.target.selectedIndex];
    if (selectedOption.value === typeElements.dev) {
        renderElements(typeElements.dev);
    }else if (selectedOption.value === typeElements.consu){
        renderElements(typeElements.consu);
    }else{
        renderElements();
    }
    
});

previewElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    console.log(e.target);

});

nextElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    console.log(e.target);
});

