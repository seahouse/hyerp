<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class Itemsite extends Model
{
    //
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'qtyonhand',
    ];
    
    public function item() {
        return $this->hasOne('App\Item', 'id', 'item_id');
    }
}
