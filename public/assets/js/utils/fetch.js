//Headers para usar para configurar la petición fetch.
const headers = {
     "Content-Type": "application/json",
};

//Función para enviar el fetch
export const sendData = async (url, method = 'GET', parameter = {}, data = {})=>{
    let setUri = setFetch(url,method,parameter,data);
    let response = await fetch(setUri,url);
    return response.json();

};

//Función para establecer el fetch.
const setFetch = (url, method = 'GET', parameters = {}, data = {})=>{
    
    //Aca creo los parámetros si necesito enviarlos.
    if (parameters) {
        const setParameters = new URLSearchParams();
    }


    const bodyFetch ={
        url: url,
        method: method,
        body: JSON.stringify(data),
        headers : headers
    }

    return bodyFetch;

}



export default{
    sendData
}