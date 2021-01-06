<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poitem_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vpoitem';
	protected $old_db = true;

	public function item() {
        return $this->hasOne('App\Models\Product\Itemp_hxold', 'goods_id', 'item_id');
    }

    public function item2() {
        return $this->hasOne('App\Models\Product\Itemp_hxold2', 'goods_no', 'goods_no2');
    }

    public function suppliermaterials() {
	    return $this->hasMany(SupplierMaterial_hxv::class, 'goods_id', 'item_id');
    }
}
