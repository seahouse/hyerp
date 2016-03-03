<?php

namespace App\models\Product;

use Illuminate\Database\Eloquent\Model;

class Bomitem extends Model
{
    //
    protected $fillable = [
        'parent_item_id',
        'item_id',
        'qtyper',
    ];
    
    public function parentitem()
    {
        return $this->hasOne('App\models\Product\Item', 'id', 'parent_item_id');
    }
    
    public function item()
    {
        return $this->hasOne('App\models\Product\Item', 'id', 'item_id');
    }
}
