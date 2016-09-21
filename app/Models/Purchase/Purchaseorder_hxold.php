<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder_hxold extends Model
{
    //
    protected $table = 'vpurchaseorder';
	protected $connection = 'sqlsrv';

	/**
     * Get the phone record associated with the user.
     */
    public function payments()
    {
        return $this->hasMany('App\Models\Purchase\Payment_hxold', 'pohead_id', 'id');
    }

    public function sohead() {
        return $this->hasOne('App\Models\Sales\Salesorder_hxold', 'id', 'sohead_id');
    }
}
