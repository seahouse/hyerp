<?php

namespace App\Models\Product;

class Itemp_hxold extends \App\Models\HxModel
{
    // item model, for purchase, hxold
    protected $table = "VGoods";
    protected $old_db = true;

    public function receiptitems()
    {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'item_number', 'goods_no');
    }

    public function shipitems()
    {
        return $this->hasMany('App\Models\Inventory\Shipitem_hxold', 'item_number', 'goods_no');
    }
}
