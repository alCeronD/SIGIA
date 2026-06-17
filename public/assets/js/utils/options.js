// archivo con objetos que tienen configuraciones de las instancias de materialize.

// options del select materialize (generado por gemini).
export const optionsSelect = {
  // Arreglo de strings. Clases de Materialize que se le añadirán
  // al contenedor del dropdown (ej: ['my-class', 'another-class'])
  classes: '',

  // Booleano. Si es true, el dropdown del select se alineará
  // al borde derecho del input.
  dropdownOptions: {
    alignment: 'left', // 'left' o 'right'
    autoFocus: true, // Enfoca el primer elemento al abrirse
    constrainWidth: true, // Fuerza al dropdown a tener el mismo ancho que el input
    container: null, // Elige un elemento del DOM para renderizar el dropdown dentro de él
    coverTrigger: false, // Si es true, el dropdown cubrirá el select al abrirse
    closeOnClick: true, // Cierra el dropdown al hacer click en una opción
    hover: false, // Si es true, el dropdown se abre al pasar el mouse por encima
    inDuration: 150, // Duración de la animación de apertura en ms
    outDuration: 250, // Duración de la animación de cierre en ms
    onOpenStart: null, // Función callback que se ejecuta al iniciar la apertura
    onOpenEnd: null, // Función callback que se ejecuta al terminar la apertura
    onCloseStart: null, // Función callback que se ejecuta al iniciar el cierre
    onCloseEnd: null, // Función callback que se ejecuta al terminar el cierre
  },
};
