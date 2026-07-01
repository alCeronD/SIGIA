<?php

interface ConfigInterface
{
  public function createRoutes(); //Function para definir el rastro de miga
  public function getFilesJs(): array;
  public function getFilesCss(): array;
  public function renderView(string $pathView = '', string $nameFunction = ''); //Function generica para renderizar la vista
}
