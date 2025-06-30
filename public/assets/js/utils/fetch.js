//Headers para usar para configurar la petición fetch.
const headers = {
     "Content-Type": "application/json",
     "X-Requested-With": "XMLHttpRequest"
};

//Función para enviar el fetch
export const sendData = async (url, method = 'GET', parameters = {}, data = {})=>{
    try {

        const setParameter = new URLSearchParams();
        setParameter.append('action', parameters);

        let newUrl = parameters ? `${url}?${setParameter}` : url;
        const optionsFetch = setFetch(method,parameters,data);
        const response = await fetch(newUrl,optionsFetch);
        const json = await response.json();

        console.log(json);

        if (!response.ok) {
            throw new Error(`Error al devolver la data ${response.status}`);
        }


        return json;

    } catch (error) {
        throw new Error(`${error}`);
        
    }

};

//Función para establecer el fetch.
const setFetch = (method = 'GET', parameters = {}, data = {})=>{
    
    //Aca creo los parámetros si necesito enviarlos.
    if (parameters) {
        const setParameters = new URLSearchParams();
        setParameters.append('action',parameters);
    }

    return {
        method,
        body: method != 'GET' ? JSON.stringify(data) : undefined,
        headers
    };

}



export default{
    sendData
}