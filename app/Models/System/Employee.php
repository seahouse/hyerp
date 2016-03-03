<?php

namespace App\System;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'number',
        'name',
        'active',
        'contact_id',
        'dept_id',
        'notes',
        'image_id',
        'startdate',
        'enddate',
    ];
    
    public function dept() {
        return $this->hasOne('App\System\Dept', 'id');
    }
}
