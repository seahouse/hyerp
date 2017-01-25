<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Itemp_hxold extends Model
{
    // item model, for purchase, hxold
    protected $table = 'VGoods';
	protected $connection = 'sqlsrv';

	public function receiptitems() {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'item_number', 'goods_no')->orderBy('record_at', 'desc');;
    }
}
