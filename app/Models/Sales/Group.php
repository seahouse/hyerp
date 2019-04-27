<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'group';
    protected $connection = 'sqlsrv';

    public function projects() {
        return $this->hasMany('App\Models\Sales\Project_hxold');
    }
}
