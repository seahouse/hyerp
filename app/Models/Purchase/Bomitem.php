<?php

namespace App;

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
        return $this->hasOne('App\Item', 'id', 'parent_item_id');
    }
    
    public function item()
    {
        return $this->hasOne('App\Item', 'id', 'item_id');
    }
}
