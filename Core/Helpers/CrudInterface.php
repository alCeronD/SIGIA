<?php
// interfaz básica para el crud del controlador, es lo minimo que debe de tener el controller.
interface CrudInterface
{
  public function getData(); //get
  public function save(); // update
  public function delete(); //Delete
  public function store(); //Insert
}
