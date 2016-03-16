<?php

namespace App\God\Controllers\System;

use DB;

class PermissionController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'permissions';
        $this->viewTitle = trans('system.permission.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('system.permission.id'),
            ],
            'name' => [
                'show' => trans('system.permission.name'),
                'search' => "name like '%%%s%%'",
            ],
            'label' => [
                'show' => trans('system.permission.label'),
            ],
            'description' => [
                'show' => trans('system.permission.description'),
            ],
            'created_at' => [
                'show' => trans('system.permission.created_at'),
            ],
            'updated_at' => [
                'show' => trans('system.permission.updated_at'),
            ],
        ];
        $this->fields_index = [      'name', 'label'];
        $this->fields_show  = ['id', 'name', 'label', 'description', 'created_at', 'updated_at'];
        $this->fields_create= [      'name', 'label', 'description'];
        $this->fields_edit  = [      'name', 'label', 'description'];
        parent::__construct();
    }
}
