<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddingproject extends Model
{
    //
    protected $table = 'project';
    protected $connection = 'sqlsrv';

    public function biddinginformation() {
        return $this->hasMany('App\Models\Basic\Biddinginformation','biddingprojectid','id');
    }
}
