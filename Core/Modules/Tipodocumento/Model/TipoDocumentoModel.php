<?php

class TipoDocumentoModel extends Crud
{
  protected $id = "tp_id";
  protected $table = "tipo_documento";
  protected $campos = [
    'tp_sigla',
    'tp_nombre',
    'tp_status'
  ];
}
