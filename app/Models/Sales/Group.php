<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Group extends \App\Models\HxModel
{
    //
    protected $table = 'group';
    

    public function projects() {
        return $this->hasMany('App\Models\Sales\Project_hxold');
    }
}
