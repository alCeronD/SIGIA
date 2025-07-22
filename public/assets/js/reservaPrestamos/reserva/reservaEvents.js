export const eventsClick = (button)=>{
    button.addEventListener('click', (e)=>{
        e.stopPropagation();
        e.preventDefault();

        alert('prueba general');

    });

};