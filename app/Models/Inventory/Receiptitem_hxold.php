<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Receiptitem_hxold extends Model
{
    //
    protected $table = 'vreceiptitem';
	protected $connection = 'sqlsrv';

	public function item() {
        return $this->hasOne('App\Models\Product\Itemp_hxold', 'goods_no', 'item_number');
    }
}
