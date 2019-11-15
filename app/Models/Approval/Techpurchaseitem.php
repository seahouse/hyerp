<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Techpurchaseitem extends Model
{
    //
    protected $fillable = [
        'techpurchase_id',
        'item_id',
//        'itemunit',
        'quantity',
        'descrip',
//        'seq',
    ];

    public function item() {
        return $this->belongsTo('\App\Models\Product\Itemp_hxold', 'item_id', 'goods_id');
    }
}
