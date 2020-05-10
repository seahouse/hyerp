<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformation extends Model
{
    //
    protected $fillable = [
        'number',
        'year',
        'digital_number',
        'closed',
        'remark',
        'sohead_id',
    ];

    public function biddinginformationitems() {
        return $this->hasMany('App\Models\Basic\Biddinginformationitem');
    }

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold','id','sohead_id');
    }

    public function biddinginformationfieldtypes() {
        return $this->hasMany('App\Models\Basic\Biddinginformationfieldtype');
    }
}
