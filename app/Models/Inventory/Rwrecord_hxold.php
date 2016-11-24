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
}
