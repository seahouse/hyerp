<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addr extends Model
{
    //
    protected $fillable = [
        'province_id',
        'city_id',
        'line1',
    ];
    
    public function province() {
//         return $this->hasOne('App\Province', 'id', 'province_id');
        return $this->belongsTo('App\Province');
    }
    
    public function city() {
        return $this->belongsTo('App\City');
    }
}
