/**
 * Description - Función para ejecutar procesos de localStorage para manipular información.
 *
 * @type {{ addValue: ({ key, item }?: { key?: string; item?: string; }) => void; }}
 */
export const Storage = {
  addValue: ({ key = '', item = '' } = {}) => {
    window.localStorage.setItem(key, item);
  },
  getValue: (key) => {
    return window.localStorage.getItem(key);
  },
};

export const getValue = (key) => {
  return JSON.parse(window.localStorage.getItem(key));
};

export const deleteDataStorage = (key) => {
  sessionStorage.removeItem(key);
};
