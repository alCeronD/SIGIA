
/**
 * Description - Función para ejecutar procesos de localStorage
 *
 * @type {{ addValue: ({ key, item }?: { key?: string; item?: string; }) => void; }}
 */
export const Storage = {
    addValue:({key="", item=""}={})=>{
        window.localStorage.setItem(key,item);
    },
    getValue: (key)=>{
        return window.localStorage.getItem(key);
    }
};
