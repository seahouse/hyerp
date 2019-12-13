<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pppayment extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    private static $approvaltype_name = "生产加工单结算付款";

    protected $fillable = [
        'productioncompany',
        'designdepartment',
        'paymentreason',
        'invoicingsituation',
        'totalpaid',
        'amount',
        'paymentdate',
        'supplier_id',
        'vendbank_id',
        'applicant_id',
        'status',
        'approversetting_id',
        'process_instance_id',
        'business_id',
        'syncdtdesc',
    ];

    public function approvers() {
        $approvers = config('custom.dingtalk.approversettings.pppayment.' . $this::getAttribute('designdepartment'), '');
        return $approvers;
    }

    public function pppaymentitems()
    {
        return $this->hasMany('\App\Models\Approval\Pppaymentitem', 'pppayment_id', 'id');
    }

    public function pppaymentitemunitprices()
    {
        return $this->hasManyThrough('\App\Models\Approval\Pppaymentitemunitprice', '\App\Models\Approval\Pppaymentitem', 'pppayment_id', 'pppaymentitem_id', 'id');
    }

    public function applicant() {
        return $this->hasOne('\App\Models\System\User', 'id', 'applicant_id');
    }
}
