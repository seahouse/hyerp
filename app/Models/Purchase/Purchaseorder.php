<?php

namespace App\Purchase;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder extends Model
{
    //
    protected $table = 'poheads';
    
    protected $fillable = [
        'status',
        'number',
        'orderdate',
        'vendinfo_id',
        'fob',
        'shipvia',
        'comments',
        'freight',
        'term_id',
        'vend_contact_id',
        'vendaddress',
        'shipto_account_id',
        'shiptoaddress',
        'sohead_id',
        'releasedate',
    ];
    
    public function vendinfo() {
        return $this->hasOne('App\Purchase\Vendinfo', 'id', 'vendinfo_id');
    }
    
    public function sohead() {
        return $this->hasOne('App\Sales\Salesorder', 'id', 'sohead_id');
    }
    
    public function poitems() {
        return $this->hasMany('App\Purchase\Poitem', 'pohead_id', 'id');
    }
}
