<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Itemp_hxold_t extends \App\Models\HxModel
{
    // item model, for purchase, hxold
    protected $table = 'goods';
	protected $old_db = true;
    public $timestamps = false;

//	public function receiptitems() {
//        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'item_number', 'goods_no');;
//    }
}
