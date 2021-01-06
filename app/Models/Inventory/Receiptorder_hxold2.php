<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Receiptorder_hxold2 extends \App\Models\HxModel
{
    //
    protected $table = 'vreceiptorder2';
    

    // public function receiptitems() {
    //        return $this->hasMany('App\Models\Inventory\Receiptitem_hxold', 'receipt_id', 'receipt_id');
    //    }

    //    public function rwrecord() {
    //        return $this->hasOne('App\Models\Inventory\Rwrecord_hxold', 'id', 'receipt_id');
    //    }

    public function pohead()
    {
        return $this->hasOne('App\Models\Purchase\Purchaseorder_hxold2', 'id', 'pohead_id');
    }
}
