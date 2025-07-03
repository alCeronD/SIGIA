//Headers para usar para configurar la petición fetch.
const headers = {
     "Content-Type": "application/json",
     "X-Requested-With": "XMLHttpRequest"
};

//Función para enviar el fetch
export const sendData = async (url, method = 'POST', parameters = {}, data = {})=>{
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
const setFetch = (method = 'GET',data = {})=>{
    
    return {
        method,
        body: method != 'GET' ? JSON.stringify(data) : undefined,
        headers
    };

}

//Función para solicitar data.
export const getData = async(url, method = 'GET', parameters = {}, data ={})=>{
    try {
        let newUrl = '';
        //Aca creo los parámetros si necesito enviarlos.
        if (parameters) {
            const setParameters = new URLSearchParams();
            Object.entries(parameters).forEach(([key,value])=>{
                setParameters.append(key,value);
            });
            
            newUrl = parameters ? `${url}?${setParameters.toString()}` : url;
            console.log(newUrl);
        }
        const bodyData = setFetch(method,parameters,data);
        const execute = await fetch(newUrl,bodyData);
          
        const getResponse = await execute.json(); 
        return getResponse;

} catch (error) {
        throw new Error(`Error de procedimiento ${error}`);
        
    }
    
}

export default{
    sendData,
    getData
}