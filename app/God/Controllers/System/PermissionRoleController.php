<?php

namespace App\God\Controllers\System;

use DB;

class PermissionRoleController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'permission_role';
        $this->viewTitle = trans('system.permission_role.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('system.permission_role.id'),
            ],
            'permission_id' => [
                'show' => trans('system.permission_role.permission_name'),
                'foreign_values' => DB::table('permissions')->lists('name', 'id'),
                'search' => "permissions.id from permissions where permissions.name like '%%%s%%'",
            ],
            'role_id' => [
                'show' => trans('system.permission_role.role_name'),
                'foreign_values' => DB::table('roles')->lists('name', 'id'),
                'search' => "roles.id from roles where roles.name like '%%%s%%'",
            ],
        ];
        $this->fields_index = [      'permission_id', 'role_id'];
        $this->fields_show  = ['id', 'permission_id', 'role_id'];
        $this->fields_create= [      'permission_id', 'role_id'];
        $this->fields_edit  = [      'permission_id', 'role_id'];
        parent::__construct();
    }
}
