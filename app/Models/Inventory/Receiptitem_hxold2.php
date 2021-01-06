<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Receiptitem_hxold2 extends \App\Models\HxModel
{
    //
    protected $table = 'vreceiptitem2';
	protected $old_db = true;

	public function item() {
        return $this->hasOne('App\Models\Product\Itemp_hxold2', 'goods_no', 'item_number');
    }

    public function rwrecord() {
        return $this->hasOne('App\Models\Inventory\Rwrecord_hxold2', 'id', 'receipt_id');
    }
}
