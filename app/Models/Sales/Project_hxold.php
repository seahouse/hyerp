<?php

namespace App\Models\Sales;

use App\Models\Basic\Biddinginformation;

class Project_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vproject';

    public function soheads()
    {
        return $this->hasMany(Salesorder_hxold::class, 'project_id', 'id');
    }

    public function biddinginformations()
    {
        return $this->hasManyThrough(Biddinginformation::class, Salesorder_hxold::class, 'project_id', 'sohead_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
