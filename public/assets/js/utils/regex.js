

export const validationRules = {
  documento: {
    regex: /^\d{5,15}$/,
    message: "El documento debe contener solo números (5 a 15 caracteres)."
  }
};


export const validateData = {
  placa:{
    // se coloca el \- para evitar que se interprete como un rango de caracteres.
    regex: /^[^#$%^&*()+_!=\-]+$/
  }
}