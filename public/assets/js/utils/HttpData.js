class HttpData {
  headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  };
  setFetch(method = 'GET', data = {}) {
    let returnPrueba = {
      method,
      body: method != 'GET' ? JSON.stringify(data) : undefined,
      headers: this.headers,
    };

    return returnPrueba;
  }
  async sendData(url, method = 'POST', data = {}) {
    try {
      let newUrl = url;

      const optionsFetch = this.setFetch(method, data);
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
      return error;
    }
  }

  async getData(url, method = 'GET', parameters = {}, asText = false, data = {}) {
    try {
      let newUrl = '';
      //Aca creo los parámetros si necesito enviarlos.
      if (parameters) {
        const setParameters = new URLSearchParams();
        Object.entries(parameters).forEach(([key, value]) => {
          setParameters.append(key, value);
        });
        // newUrl = parameters ? `${url}?${setParameters.toString()}` : url;

        newUrl = JSON.stringify(parameters) === '{}' ? url : `${url}&${setParameters.toString()}`;
      }

      const bodyData = this.setFetch(method, parameters, data);
      const execute = await fetch(newUrl, bodyData);
      if (execute.status === 204) {
        return { status: 204 };
      }

      const getResponse = asText ? await execute.text() : await execute.json();
      return getResponse;
    } catch (error) {
      throw new Error(`Error de procedimiento ${error}`);
    }
  }
}
