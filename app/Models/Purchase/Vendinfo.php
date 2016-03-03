<?php

namespace App\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendinfo extends Model
{
    //
    protected $fillable = [
        'name',
        'active',
        'po',
        'comments',
        'number',
        'term_id',
        'shipvia',
        'vendtype_id',
        'contact1_id',
        'contact2_id',
        'addr_id',
    ];
    
    public function poitems() {
        return $this->hasManyThrough('App\Purchase\Poitem', 'App\Purchase\Purchaseorder', 'vendinfo_id', 'pohead_id');
    }
    
    public function payments() {
        return $this->hasManyThrough('App\Purchase\Payment', 'App\Purchase\Purchaseorder', 'vendinfo_id', 'pohead_id');
    }
}
