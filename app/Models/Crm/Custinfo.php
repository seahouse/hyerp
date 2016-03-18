<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

class Custinfo extends Model
{
    //
    protected $fillable = [
        'number',
        'name',
        'contact_id',
        'comments',
    ];
    
    public function contact() {
        return $this->hasOne('App\Contact', 'id', 'contact_id');
    }
    
    public function soheads() {
        return $this->hasMany('App\Sales\Salesorder', 'custinfo_id', 'id');
    }
    
    public function soitems() {
        return $this->hasManyThrough('App\Sales\Soitem', 'App\Sales\Salesorder', 'custinfo_id', 'sohead_id');
    }
    
    public function receiptpayments() {
        return $this->hasManyThrough('App\Sales\Receiptpayments', 'App\Sales\Salesorder', 'custinfo_id', 'sohead_id');
    }
    
    public function sototalprice() {
        return '0.0';
    }
}
