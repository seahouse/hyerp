<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Mcitempurchaseitem extends Model
{
    //
    protected $fillable = [
        'mcitempurchase_id',
        'item_id',
        'size',
        'material',
        'unitprice',
        'quantity',
        'unit_id',
        'weight',
        'remark',
        'seq',
    ];

    public function item() {
        return $this->belongsTo('\App\Models\Product\Itemp_hxold', 'item_id', 'goods_id');
    }
}
