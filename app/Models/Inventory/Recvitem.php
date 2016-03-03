<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class Recvitem extends Model
{
    //
    protected $fillable = [
        'orderitem_id',
        'recvhead_id',
        'quantity',
        'recvdate',
    ];
}
