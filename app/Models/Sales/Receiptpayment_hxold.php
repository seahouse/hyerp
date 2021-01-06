<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Receiptpayment_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vreceiptpayment';
    

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold', 'id', 'sohead_id');
    }
}
