import { getData } from "../utils/fetch.js";

export const renderElement = (async ({action = 'getElement', value = ''} = {})=>{
    try {
        let dataResult = await getData(
        'modules/elementos/controller/elementosController.php',
        'GET',
        { action,valueInput: value});
        console.log(value);

        console.log(dataResult);
    } catch (error) {
        throw new Error(`Error de ejecución ${error}`);
    }

});