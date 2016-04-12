<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poitem extends Model
{
    //
    protected $fillable = [
        'status',
        'pohead_id',
        'itemsite_id',
        'duedate',
        'qty_ordered',
        'unitprice',
        'comments',
        'freight',
    ];
    
    public function pohead() {
        return $this->hasOne('App\Models\Purchase\Purchaseorder', 'id', 'pohead_id');
    }
    
    public function itemsite() {
        return $this->hasOne('App\Models\Inventory\Itemsite', 'id', 'itemsite_id');
    }
}
