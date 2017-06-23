<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Rwrecord_hxold extends Model
{
    //receive warehouse
    
    protected $table = 'vrwrecord';
	protected $connection = 'sqlsrv';

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'receipt_id', 'id');
    }

    public function supplier() {
    	return $this->hasOne('App\Models\Purchase\Vendinfo_hxold', 'id', 'supplier_id');
    }

    // 目前仅考虑一一对应关系。实际上是有可能一个入库单对应多个采购订单的
    public function receiptorder() {
        return $this->hasOne('App\Models\Inventory\Receiptorder_hxold', 'receipt_id', 'id');
    }

    public function warehouse() {
        return $this->hasOne('App\Models\Inventory\Warehouse_hxold', 'number', 'warehouse_number');
    }

    public function handler() {
	    return $this->hasOne('App\Models\System\Employee_hxold', 'id', 'handler_id');
    }
}
