<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Projectsitepurchaseitem extends Model
{
    //
    protected $fillable = [
        'projectsitepurchase_id',
        'item_id',
        'brand',
        'unit_id',
        'quantity',
        'unitprice',
        'price',
        'seq',
    ];

    public function item() {
        return $this->belongsTo('\App\Models\Product\Itemp_hxold', 'item_id', 'goods_id');
    }

    public function unit() {
        return $this->belongsTo('\App\Models\Product\Unit_hxold');
    }
}
