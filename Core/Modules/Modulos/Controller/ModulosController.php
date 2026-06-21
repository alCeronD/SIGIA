<?php

class ModulosController implements CrudInterface
{
  protected ServicesModulos $sModulos;

  public function __construct()
  {
    $sModulos = new ServicesModulos();
  }

  #[Override]
  public function getData()
  {
    throw new \Exception('Not implemented');
  }

  #[Override]
  public function save()
  {
    throw new \Exception('Not implemented');
  }

  #[Override]
  public function store()
  {
    throw new \Exception('Not implemented');
  }

  #[Override]
  public function delete()
  {
    throw new \Exception('Not implemented');
  }
}
