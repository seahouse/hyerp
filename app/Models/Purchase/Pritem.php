<?php

namespace App\Models\Purchase;

use App\Models\Product\Itemp_hxold;
use Illuminate\Database\Eloquent\Model;

class Pritem extends Model
{
    //
    protected $fillable = [
        'prhead_id',
        'item_id',
        'quantity',
        'remark',
    ];

    public function prhead() {
        return $this->belongsTo(Prhead::class);
    }

    public function item() {
        return $this->belongsTo(Itemp_hxold::class, 'item_id', 'goods_id');
    }
}
