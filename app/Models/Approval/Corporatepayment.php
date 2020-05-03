<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corporatepayment extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'suppliertype',
        'paymenttype',
        'position',
        'amounttype',
        'remark',
        'amount',
        'paydate',
        'paymentmethod',
        'supplier_id',
        'vendbank_id',
        'associated_approval_projectpurchase',

        'applicant_id',
        'status',
        'process_instance_id',
        'business_id',
    ];
}
