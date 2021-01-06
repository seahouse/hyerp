<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Rwrecord_hxold2 extends \App\Models\HxModel
{
    //receive warehouse
    
    protected $table = 'vrwrecord2';
	

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold2', 'receipt_id', 'id');
    }

    public function supplier() {
    	return $this->hasOne('App\Models\Purchase\Vendinfo_hxold2', 'id', 'supplier_id');
    }

    // 目前仅考虑一一对应关系。实际上是有可能一个入库单对应多个采购订单的
    public function receiptorder() {
        return $this->hasOne('App\Models\Inventory\Receiptorder_hxold2', 'receipt_id', 'id');
    }
}
