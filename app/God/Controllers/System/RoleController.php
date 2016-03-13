<?php

namespace App\God\Controllers\System;

use DB;

class RoleController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'roles';
        $this->viewTitle = trans('system.role.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('system.role.id'),
            ],
            'name' => [
                'show' => trans('system.role.name'),
                'search' => "name like '%%%s%%'",
            ],
            'label' => [
                'show' => trans('system.role.label'),
            ],
            'description' => [
                'show' => trans('system.role.description'),
            ],
            'created_at' => [
                'show' => trans('system.role.created_at'),
            ],
            'updated_at' => [
                'show' => trans('system.role.updated_at'),
            ],
        ];
        $this->fields_index = [      'name', 'label'];
        $this->fields_show  = ['id', 'name', 'label', 'description', 'created_at', 'updated_at'];
        $this->fields_create= [      'name', 'label', 'description'];
        $this->fields_edit  = [      'name', 'label', 'description'];
        parent::__construct();
    }
}
