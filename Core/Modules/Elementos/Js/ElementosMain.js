// archivo javascript que esta asociado a la vista principal del modulo, en este archivo haremos peticiones a los servicios de categorias, marcas, areas, entre otros.
class ElementosMain {
  constructor(parameters) {}

  getCategorias() {}

  getDepartamentos() {}

  getMarcas() {}

  getData() {
    const categorias = this.getCategorias();
    const departamentos = this.getDepartamentos();
    const marcas = this.getMarcas();
  }
}
