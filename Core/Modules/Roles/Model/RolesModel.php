<?php

class RolesModel extends Crud
{

    protected $table = 'roles';
    protected $id = 'rl_id';
    protected $campos = [
        'rl_nombre',
        'rl_descripcion',
        'rl_status'
    ];
}
