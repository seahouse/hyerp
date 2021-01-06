<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receiptitem_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vreceiptitem';
    protected $old_db = true;

    public function item()
    {
        return $this->hasOne('App\Models\Product\Itemp_hxold', 'goods_no', 'item_number');
    }

    public function rwrecord()
    {
        return $this->hasOne('App\Models\Inventory\Rwrecord_hxold', 'id', 'receipt_id');
    }

    //    public function outSoheadNamesByBatch() {
    //	    DB::connection()
    //    }
}
