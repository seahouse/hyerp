<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformationitem extends Model
{
    //
    protected $fillable = [
        'biddinginformation_id',
        'key',
        'value',
        'remark',
        'sort',
        'type',
    ];

    public function biddinginformation() {
        return $this->belongsTo('App\Models\Basic\Biddinginformation');
    }

    public function biddinginformationdefinefield() {
        return $this->belongsTo('App\Models\Basic\Biddinginformationdefinefield', 'key', 'name');
    }

    public function biddinginformationitemmodifylogs() {
        return $this->hasMany('App\Models\Basic\Biddinginformationitemmodifylog');
    }
}
