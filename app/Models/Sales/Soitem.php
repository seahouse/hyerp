<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Soitem extends Model
{
    //
    protected $fillable = [
        'sohead_id',
        'item_id',
        'scheddate',
        'qty',
        'unitcost',
        'price',
        'custprice',
        'qtyshipped',
    ];
    
    public function sohead() {
        return $this->hasOne('App\Sales\Salesorder', 'id', 'sohead_id');
    }
    
    public function item() {
        return $this->hasOne('App\Models\Product\Item', 'id', 'item_id');
    }
    
    public function itemsite() {
        return $this->hasOne('App\Models\Inventory\Itemsite', 'item_id', 'item_id');
    }
}
