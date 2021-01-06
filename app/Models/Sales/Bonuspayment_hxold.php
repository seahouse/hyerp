<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Bonuspayment_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'bonuspayment';
    

    protected $fillable = [
        'sohead_id',
        'paymentdate',
        'bonusfactor',
        'amountpertenthousandbysohead',
        'amount',
        'remark',
    ];
}
