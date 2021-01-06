<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Equipmenttypeass_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'equipmenttypeass';
    protected $old_db = true;

    public $timestamps = false;
    public $primaryKey = 'equipmenttypeass_id';

    protected $fillable = [
        'equipmenttypeass_order_id',
        'equipmenttypeass_equipmenttype_id',
    ];
}
