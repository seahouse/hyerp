<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poheadtaxrateass_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'poheadtaxrateass';
    protected $old_db = true;

    protected $fillable = [
        'name',
        'pohead_id',
        'descrip',
        'amount',
        'taxrate'
    ];
}
