<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Project_hxold extends Model
{
    //
    protected $table = 'vproject';
    protected $connection = 'sqlsrv';


    public function soheads() {
//        return $this->belongsToMany('App\Models\Sales\Salesorder_hxold', 'projectorders', 'project_id', 'order_id');
        return $this->hasMany('App\Models\Sales\Salesorder_hxold', 'project_id', 'id');
    }
}
