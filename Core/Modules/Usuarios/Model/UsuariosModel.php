<?php

class UsuariosModel extends Crud
{

    protected $id = "usu_id";
    protected $table = "usuarios";
    protected $campos = [
        'usu_docum',
        'usu_nombres',
        'usu_apellidos',
        'usu_password',
        'usu_email',
        'usu_direccion',
        'usu_telefono',
        'usu_observacion',
        'usu_id_estado',
        'usu_tp_id',
        'created_at',
        'updated_at'
    ];
}
