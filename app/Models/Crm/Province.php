<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    
    public function cities() {
        return $this->hasMany('\App\Models\Crm\City');
    }
}
