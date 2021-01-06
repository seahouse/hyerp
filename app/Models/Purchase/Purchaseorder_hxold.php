<?php

namespace App\Models\Purchase;

use App\Models\Approval\Corporatepayment;
use App\Models\Approval\Vendordeduction;
use App\Models\Approval\Vendordeductionitem;
use App\Models\Product\Itemp_hxold;
use App\Models\System\Employee_hxold;
use Illuminate\Database\Eloquent\Model;

class Purchaseorder_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vpurchaseorder';
	protected $old_db = true;

	/**
     * Get the phone record associated with the user.
     */
    public function payments()
    {
        return $this->hasMany('App\Models\Purchase\Payment_hxold', 'pohead_id', 'id');
    }

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold', 'id', 'sohead_id');
    }

    public function poitems() {
        return $this->hasMany('App\Models\Purchase\Poitem_hxold', 'pohead_id', 'id');
    }

    public function receiptorders() {
        return $this->hasMany('App\Models\Inventory\Receiptorder_hxold', 'pohead_id', 'id');
    }

    public function vendinfo() {
        return $this->hasOne('App\Models\Purchase\Vendinfo_hxold', 'id', 'vendinfo_id');
    }

    public function applicant() {
        return $this->belongsTo(Employee_hxold::class);
    }

    public function operator() {
        return $this->belongsTo(Employee_hxold::class);
    }

    public function vendordeductionitems() {
        return $this->hasManyThrough(Vendordeductionitem::class, Vendordeduction::class, 'pohead_id');
    }

    // 采购部录入的到票记录
    public function purchasetickets() {
        return $this->hasMany(Purchaseticket_hxold::class, 'pohead_id');
    }

    // 对公付款审批单
    public function corporatepayments() {
        return $this->hasMany(Corporatepayment::class, 'pohead_id');
    }
}
