<?php

namespace App\Models\Approval;

use App\Models\Purchase\Purchaseorder_hx;
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
        'sohead_id',
        'pohead_id',
        'remark',
        'amountpercent',
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

    public function sohead() {
        $this->belongsTo(Purchaseorder_hx::class);
    }
}
