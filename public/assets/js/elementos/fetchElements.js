import { getData } from "../utils/fetch.js";

/**
 * Obtiene elementos desde el backend mediante una petición GET con filtros de tipo y paginación.
 *
 * @async
 * @function getElements
 * @param {string} [type='all'] - Tipo de elemento a consultar. Valores permitidos: `'all'`, `'consumible'`, `'devolutivo'`.
 * @param {string} [action='elements'] - Acción que será enviada como parámetro al controlador PHP.
 * @param {number} [page=1] - Número de página para la paginación.
 * @returns {Promise<void>} - No retorna un valor explícito, pero imprime los datos en consola.
 *
 * @example
 * // Obtener todos los elementos
 * getElements();
 *
 * @example
 * // Obtener elementos de tipo consumible en la página 2
 * getElements('consumible', 'elements', 2);
 */
export const renderElements = async (type = 'all', action = 'elements', page = 1) => {
    try {
        const dataElements = await getData(
        'modules/elementos/controller/elementosController.php',
        'GET',
        { action, pages: page, type }
    );

    let data = dataElements.data;
    console.log(data);
    
    } catch (error) {
        throw new Error(`Error al consultar los elementos ${error}`);
                
    }
    
};
