<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Employee_hxold extends Model
{
    //
    protected $table = 'vemployee';
    protected $connection = 'sqlsrv';

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
