<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Employee_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vemployee';
    protected $old_db = true;

    // protected $fillable = [
    //     'number',
    //     'name',
    //     'active',
    //     'contact_id',
    //     'dept_id',
    //     'notes',
    //     'image_id',
    //     'startdate',
    //     'enddate',
    // ];
    
    // public function dept() {
    //     return $this->hasOne('App\Models\System\Dept', 'id');
    // }
}
