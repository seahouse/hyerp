<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Bonuspayment_hxold extends Model
{
    //
    protected $table = 'bonuspayment';
    protected $connection = 'sqlsrv';

    protected $fillable = [
        'sohead_id',
        'paymentdate',
        'bonusfactor',
        'amountpertenthousandbysohead',
        'amount',
        'remark',
    ];
}
