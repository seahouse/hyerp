<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddingproject extends Model
{
    //
    protected $fillable = [
        'name',
        'remark',
    ];

    public function biddinginformation() {
        return $this->hasMany('App\Models\Basic\Biddinginformation','biddingprojectid','id');
    }
}
