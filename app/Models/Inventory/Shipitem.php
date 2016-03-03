<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class Shipitem extends Model
{
    //
    protected $fillable = [
        'orderitem_id',
        'shiphead_id',
        'quantity',
        'shipdate',
    ];
}
