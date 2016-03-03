<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'citys';
    
    protected $fillable = [
        'name',
        'province_id'
    ];
    
    public function province() {
//         return $this->hasOne('App\Province', 'id', 'province_id');
        return $this->belongsTo('App\Province');
    }
}
