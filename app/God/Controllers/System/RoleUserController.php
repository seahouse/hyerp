<?php

namespace App\God\Controllers\System;

use DB;

class RoleUserController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'role_user';
        $this->viewTitle = trans('system.role_user.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('system.role_user.id'),
            ],
            'user_id' => [
                'show' => trans('system.role_user.user_name'),
                'foreign_values' => DB::table('users')->lists('name', 'id'),
                'search' => "users.id from users where users.name like '%%%s%%'",
            ],
            'role_id' => [
                'show' => trans('system.role_user.role_name'),
                'foreign_values' => DB::table('roles')->lists('name', 'id'),
                'search' => "roles.id from roles where roles.name like '%%%s%%'",
            ],
        ];
        $this->fields_index = [      'user_id', 'role_id'];
        $this->fields_show  = ['id', 'user_id', 'role_id'];
        $this->fields_create= [      'user_id', 'role_id'];
        $this->fields_edit  = [      'user_id', 'role_id'];
        parent::__construct();
    }
}
