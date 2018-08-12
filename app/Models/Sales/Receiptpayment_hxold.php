<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Receiptpayment_hxold extends Model
{
    //
    protected $table = 'vreceiptpayment';
    protected $connection = 'sqlsrv';

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold', 'id', 'sohead_id');
    }
}
