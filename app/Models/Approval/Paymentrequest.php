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

    public function supplier_hxold() {
        return $this->hasOne('\App\Models\Purchase\Vendinfo_hxold', 'id', 'supplier_id');
    }

    public function purchaseorder_hxold() {
        return $this->hasOne('\App\Models\Purchase\Purchaseorder_hxold', 'id', 'pohead_id');
    }
}
