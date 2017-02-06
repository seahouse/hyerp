<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Itemp_hxold_t extends Model
{
    // item model, for purchase, hxold
    protected $table = 'goods';
	protected $connection = 'sqlsrv';
    public $timestamps = false;

//	public function receiptitems() {
//        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'item_number', 'goods_no');;
//    }
}
