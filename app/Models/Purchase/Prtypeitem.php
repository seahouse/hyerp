<?php

namespace App\Models\Purchase;

use App\Models\Product\Itemp_hxold;
use Illuminate\Database\Eloquent\Model;

class Prtypeitem extends Model
{
    //
    protected $fillable = [
        'prtype_id',
        'item_id',
        'quantity',
    ];

    public function prtype() {
        return $this->belongsTo(Prtype::class);
    }

    public function item() {
        return $this->belongsTo(Itemp_hxold::class, 'item_id', 'goods_id');
    }
}
