<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Rwrecord_hxold2 extends Model
{
    //receive warehouse
    
    protected $table = 'vrwrecord2';
	protected $connection = 'sqlsrv';

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold2', 'receipt_id', 'id');
    }

    public function supplier() {
    	return $this->hasOne('App\Models\Purchase\Vendinfo_hxold2', 'id', 'supplier_id');
    }
}
