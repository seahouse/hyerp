<?php

namespace App\Inventory;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $fillable = [
        'code',
        'descrip',
        'active',
        'shipping_commission',
        'contact_id',
        'addr_id',
    ];
    
    public function contact() {
        return $this->hasOne('App\Contact', 'id');
    }
    
    public function addr() {
        return $this->hasOne('App\Addr', 'id');
    }
}
