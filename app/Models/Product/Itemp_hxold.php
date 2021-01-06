<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Itemp_hxold extends Model
{
    // item model, for purchase, hxold
    // protected $table;
    // protected $connection = 'sqlsrv';

    public function __construct()
    {
        $this->table = config('database.connections.sqlsrv.database') . '.dbo.VGoods';
    }

    public function receiptitems()
    {
        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'item_number', 'goods_no');
    }

    public function shipitems()
    {
        return $this->hasMany('App\Models\Inventory\Shipitem_hxold', 'item_number', 'goods_no');
    }
}
