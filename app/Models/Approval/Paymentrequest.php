<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Paymentrequest extends Model
{
    //
    protected $fillable = [
        'descrip',
        'supplier_id',
        'pohead_id',
        'amount',
        'paymentmethod',
        'datepay',
        'bank',
        'bankaccountnumber',
        'applicant_id',
    ];
}
