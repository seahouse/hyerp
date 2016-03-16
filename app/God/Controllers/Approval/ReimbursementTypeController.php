<?php

namespace App\God\Controllers\Approval;

use DB;

class ReimbursementTypeController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'reimbursementtypes';
        $this->viewTitle = trans('approval.reimbursementtype.title');
        $this->fields_all = [
            'id' => [
                'show' => trans('approval.reimbursementtype.id'),
            ],
            'name' => [
                'show' => trans('approval.reimbursementtype.name'),
                'search' => "name like '%%%s%%'",
            ],
            'descrip' => [
                'show' => trans('approval.reimbursementtype.descrip'),
            ],
            'created_at' => [
                'show' => trans('approval.reimbursementtype.created_at'),
            ],
            'updated_at' => [
                'show' => trans('approval.reimbursementtype.updated_at'),
            ],
        ];
        $this->fields_index = ['id', 'name', 'descrip'];
        $this->fields_show  = ['id', 'name', 'descrip', 'created_at', 'updated_at'];
        $this->fields_create= ['name', 'descrip'];
        $this->fields_edit  = $this->fields_create;
        parent::__construct();
    }
}
