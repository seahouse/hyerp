<?php

namespace App\God\Controllers\System;

use DB, Illuminate\Http\Request;

class UserController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'users';
        $this->viewTitle = trans('system.user.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('system.user.id'),
            ],
            'name' => [
                'show' => trans('system.user.name'),
                'search' => "name like '%%%s%%'",
            ],
            'email' => [
                'show' => trans('system.user.email'),
            ],
            'dtuserid' => [
                'show' => trans('system.user.dtuserid'),
            ],
            'password' => [
                'show' => trans('system.user.password'),
            ],
            'remember_token' => [
                'show' => trans('system.user.remember_token'),
            ],
            'created_at' => [
                'show' => trans('system.user.created_at'),
            ],
            'updated_at' => [
                'show' => trans('system.user.updated_at'),
            ],
        ];
        $this->fields_index = [      'name', 'email', 'dtuserid'];
        $this->fields_show  = ['id', 'name', 'email', 'dtuserid', 'password', 'remember_token', 'created_at', 'updated_at'];
        $this->fields_create= [      'name', 'email', 'dtuserid', 'password'];
        $this->fields_edit  = [      'name', 'email', 'dtuserid'];
        parent::__construct();
    }

    public function store(Request $request)
    {
        $password = $request->request->get('password');
        $request->request->set('password', bcrypt($password));
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $password = $request->request->get('password');
        $request->request->set('password', bcrypt($password));
        return parent::update($request, $id);
    }
}
