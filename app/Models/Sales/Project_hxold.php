<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Project_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vproject';
    protected $old_db = true;


    public function soheads() {
//        return $this->belongsToMany('App\Models\Sales\Salesorder_hxold', 'projectorders', 'project_id', 'order_id');
        return $this->hasMany('App\Models\Sales\Salesorder_hxold', 'project_id', 'id');
    }

    public function group() {
        return $this->belongsTo('App\Models\Sales\Group');
    }


}
