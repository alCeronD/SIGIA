//Headers para usar para configurar la petición fetch.
const headers = {
  'Content-Type': 'application/json',
  'X-Requested-With': 'XMLHttpRequest',
};

//Función para establecer el fetch.
const setFetch = (method = "GET", action = "", data = {}) => {
  if (method === "GET" || method === "POST" || method === "PUT") {
    data["action"] = action;
  }

  let returnPrueba = {
    method,
    body: method != "GET" ? JSON.stringify(data) : undefined,
    headers
  };

  return returnPrueba;
};

//Función para enviar el fetch
export const sendData = async (
  url,
  method = "POST",
  parameters = {},
  data = {}
) => {
  try {
    const setParameter = new URLSearchParams();
    setParameter.append("action", parameters);

    let newUrl;

    if (Object.keys(parameters).length > 0) {
      newUrl = `${url}?${setParameter}`;
    } else {
      newUrl = url;
    }
    const optionsFetch = setFetch(method, parameters, data);
    const response = await fetch(newUrl, optionsFetch);
    if (response.status === 204) {
      return { status: 204 };
    }

    const json = await response.json();

    if (!response.ok) {
      return { status: response.status, ...json };
    }
    return json;
  } catch (error) {
    throw error;
  }
};

//Función para solicitar data.
export const getData = async (
  url,
  method = "GET",
  parameters = {},
  asText = false,
  data = {}
) => {
  try {
    let newUrl = "";
    //Aca creo los parámetros si necesito enviarlos.
    if (parameters) {
      const setParameters = new URLSearchParams();
      Object.entries(parameters).forEach(([key, value]) => {
        setParameters.append(key, value);
      });
      newUrl = parameters ? `${url}?${setParameters.toString()}` : url;
    }

    const bodyData = setFetch(method, parameters, data);
    const execute = await fetch(newUrl, bodyData);
    if (execute.status === 204) {
      return { status: 204 };
    }

    const getResponse = asText
      ? await execute.text()
      : await execute.json();
    return getResponse;
  } catch (error) {
    throw new Error(`Error de procedimiento ${error}`);
  }
};

export default {
  sendData,
  getData,
};
