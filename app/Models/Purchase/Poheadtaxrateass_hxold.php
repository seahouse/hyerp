<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poheadtaxrateass_hxold extends Model
{
    //
    protected $table = 'poheadtaxrateass';
    protected $connection = 'sqlsrv';

    protected $fillable = [
        'name',
        'pohead_id',
        'descrip',
        'amount',
        'taxrate'
    ];
}
