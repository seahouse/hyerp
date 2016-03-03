<?php

namespace App\Purchase;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'pohead_id',
        'amount',
        'paydate',
        'notes',
    ];
    
    public function pohead() {
        return $this->hasOne('App\Purchase\Purchaseorder', 'id', 'pohead_id');
    }
}
