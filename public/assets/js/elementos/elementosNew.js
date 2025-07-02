import { getData } from "../utils/fetch.js";
import {renderElements } from "./fetchElements.js";

const typeElements = {
    dev: 'devolutivo',
    consu: 'consumible',
    all: 'all'
}
let pageElement = 1;
let pageGlobal;
const filtroTipo = document.querySelector('#filtroTipo');
const previewElements = document.querySelector('#previewElements');
const nextElements = document.querySelector('#nextElements');
let currentType = typeElements.all;

document.addEventListener('DOMContentLoaded', ()=>{

    renderElements({type : typeElements.all, type: currentType});
});

//La forma en como filtro los elementos se puede cambiar.
filtroTipo.addEventListener('change', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    // reinicio a la primera Página para que siempre dependiendo del filtro, visualice la primera página como inicio.
    pageElement = 1;
    //Hacer una validación de que el valor de optión exista.
    /**
     * por ejemplo, si el optin es devolutivo o cnsumible o todo, debe ejecutar, pero si hay OTRO, debe de mostrar x defecto el todo.
     */
    const selectedOption = e.target.options[e.target.selectedIndex];
    if (selectedOption.value === typeElements.dev) {
        currentType = typeElements.dev;
    }else if (selectedOption.value === typeElements.consu){
        currentType = typeElements.consu;
    }else{
        currentType = typeElements.all;
    }    
    renderElements({type : currentType, page: pageElement});
});

previewElements.addEventListener('click', (e)=>{
    e.stopPropagation();
    e.preventDefault();
    if (pageElement <= 1 )return;
    pageElement--;
    renderElements({type: currentType ,page: pageElement});
});

nextElements.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();
    if (pageElement >= pageGlobal) return;
    pageElement++
    renderElements({type: currentType ,page: pageElement});
});