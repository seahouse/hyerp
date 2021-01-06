<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poitem_hx extends \App\Models\HxModel
{
    //
    protected $table = '采购订单商品';
    public $timestamps = false;
	protected $old_db = true;

    protected $fillable = [
        'order_id',
        'goods_id',
        'goods_name',
        'goods_no',
        'goods_indexno',
        'goods_number',
        'goods_unit',
        'goods_price',
        'goods_demo',
        'goods_state',
        'PrvID',
    ];

//	public function item() {
//        return $this->hasOne('App\Models\Product\Itemp_hxold', 'goods_id', 'item_id');
//    }
//
//    public function item2() {
//        return $this->hasOne('App\Models\Product\Itemp_hxold2', 'goods_no', 'goods_no2');
//    }
}
