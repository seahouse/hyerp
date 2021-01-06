<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder_hxold2 extends \App\Models\HxModel
{
    //
    protected $table = 'vpurchaseorder2';
	

	/**
     * Get the phone record associated with the user.
     */
    // public function payments()
    // {
    //     return $this->hasMany('App\Models\Purchase\Payment_hxold', 'pohead_id', 'id');
    // }

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold2', 'id', 'sohead_id');
    }

    // public function poitems() {
    //     return $this->hasMany('App\Models\Purchase\Poitem_hxold', 'pohead_id', 'id');
    // }

    // public function receiptorders() {
    //     return $this->hasMany('App\Models\Inventory\Receiptorder_hxold', 'pohead_id', 'id');
    // }

    // public function vendinfo() {
    //     return $this->hasOne('App\Models\Purchase\Vendinfo_hxold', 'id', 'vendinfo_id');
    // }
}
