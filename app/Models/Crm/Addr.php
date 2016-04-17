<?php

namespace App\Models\Crm;

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
        return $this->belongsTo('App\Models\Crm\Province');
    }
    
    public function city() {
        return $this->belongsTo('App\Models\Crm\City');
    }
}
