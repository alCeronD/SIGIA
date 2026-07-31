/**
 * Utility class for manipulating localStorage without requiring instantiation.
 */
export class StorageHelper {
  /**
   * Saves an item as a string in localStorage.
   * @param {{ key?: string, item?: string }} [params]
   */
  static addValue({ key = '', item = '' } = {}) {
    window.localStorage.setItem(key, item);
  }

  /**
   * Retrieves a raw string value from localStorage by key.
   * @param {string} key
   * @returns {string | null}
   */
  static getValue(key) {
    return window.localStorage.getItem(key);
  }

  /**
   * Retrieves and parses a JSON item from localStorage by key.
   * @param {{ key?: string }} [params]
   * @returns {any}
   */
  static getParsedValue({ key = '' } = {}) {
    const item = window.localStorage.getItem(key);
    if (!item) return null;

    try {
      return JSON.parse(item);
    } catch (error) {
      console.error(`Error parsing JSON for key "${key}":`, error);
      return null;
    }
  }

  /**
   * Removes an item from localStorage by key.
   * @param {string} key
   */
  static deleteData(key) {
    window.localStorage.removeItem(key);
  }
}
