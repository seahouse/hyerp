<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddingproject extends \App\Models\HxModel
{
    //
    protected $table = 'project';
    

    public function biddinginformation()
    {
        return $this->hasMany('App\Models\Basic\Biddinginformation', 'biddingprojectid', 'id');
    }
}
