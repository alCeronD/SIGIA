/**
 * Function para comparar 2 objetos, el objetivo es primero crear 1 objeto adicional con las opciones
 *
 * @param {{}} [objA={}] - El objeto en donde extraemos las llaves para comparar
 * @param {{}} [objB={}] - EL objeto que sera recorrido por el foreach
 * @param {{}} [objB={}] - El objeto a comparar
 * @returns {boolean}
 */
export const compareObjects = (objA = {}, objB = {}, objCompare2 = {}) => {
  let keys = Object.keys(objA);
  let objCompare1 = {};

  Object.entries(objB).forEach(([key2, value]) => {
    if (keys.includes(key2)) {
      objCompare1[key2] = String(value) === 'null' ? '' : String(value);
    }
  });

  return JSON.stringify(objCompare1) === JSON.stringify(objCompare2);
};
