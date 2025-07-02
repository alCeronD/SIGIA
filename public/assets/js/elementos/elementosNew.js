import { getData } from "../utils/fetch.js";
import {renderElements } from "./fetchElements.js";
const typeElements = {
    dev: 'devolutivo',
    consu: 'consumible',
    all: 'all'
}

const filtroTipo = document.querySelector('#filtroTipo');

document.addEventListener('DOMContentLoaded', ()=>{

    renderElements();
});

//La forma en como filtro los elementos se puede cambiar.
filtroTipo.addEventListener('change', (e)=>{
    e.stopPropagation();
    e.preventDefault();

    const selectedOption = e.target.options[e.target.selectedIndex];
    if (selectedOption.value === typeElements.dev) {
        renderElements(typeElements.dev);
    }else if (selectedOption.value === typeElements.consu){
        renderElements(typeElements.consu);
    }else{
        renderElements();
    }
    
});

