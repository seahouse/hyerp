<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Itemp_hxold2 extends \App\Models\HxModel
{
    // item model, for purchase, hxold
    protected $table = 'vgoods2';
	

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold2', 'item_number', 'goods_no');
    }
}
