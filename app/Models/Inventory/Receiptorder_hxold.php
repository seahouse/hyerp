<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Receiptorder_hxold extends Model
{
    //
    protected $table = 'vreceiptorder';
	protected $connection = 'sqlsrv';

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'receipt_id', 'receipt_id');
    }

    public function rwrecord() {
        return $this->hasOne('App\Models\Inventory\Rwrecord_hxold', 'id', 'receipt_id');
    }
}
