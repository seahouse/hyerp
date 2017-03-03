<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Userold extends Model
{
    //
//    protected $table = 'vemployee';
//    protected $connection = 'sqlsrv';

    public function user_hxold() {
        return $this->hasOne('App\Models\System\Employee_hxold', 'id', 'user_hxold_id');
    }
}
