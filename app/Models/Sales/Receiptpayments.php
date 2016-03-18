<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Receiptpayments extends Model
{
    //
    protected $fillable = [
        'sohead_id',
        'amount',
        'recvdate',
        'notes',
    ];
    
    public function sohead() {
        return $this->hasOne('App\Sales\Salesorder', 'id', 'sohead_id');
    }
}
