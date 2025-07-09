
let regex = new RegExp();


// Reglas
export const validationRules = {
  documento: {
    regex: /^\d{5,15}$/,
    message: "El documento debe contener solo números (5 a 15 caracteres)."
  },
  placa:{
    // se coloca el \- para evitar que se interprete como un rango de caracteres.
    regex: /^\d+$/,
    message: "La placa no debe tener caracteres especiales ni letras."
  }
};

export const  validarGenero= (genero) =>{
    let regex = /[a-zA-Z]+/g;
    //la idea de aca es validar la expresión regular e implementar el dentro de un div un span con el texto de que diga que no es optimo este genero.
    let regexNumber = /[0-1]+/g;
    generoValue.innerHTML = 'valor digitado: '+genero;
    //return alert('El genero digitado es '+genero);
    //return regex.test(genero);
    return regex.test(genero);
}

export const validatePlaca = (placa)=>{
  regex = validationRules.placa.regex;

  return regex.test(placa);

};