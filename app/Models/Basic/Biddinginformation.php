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
    ];

    public function biddinginformationitems() {
        return $this->hasMany('App\Models\Basic\Biddinginformationitem');
    }
}
