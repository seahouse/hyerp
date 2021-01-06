<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Custinfo_hxold extends Model
{
//    protected $table = 'vcustomer';
//    protected $connection = 'sqlsrv';

    //
    // protected $fillable = [
    //     'number',
    //     'name',
    //     'contact_id',
    //     'comments',
    // ];

    public function __construct()
    {
        $this->table = config('database.connections.sqlsrv.database') . '.dbo.vorder';
    }
    
    // public function contact() {
    //     return $this->hasOne('App\Models\Crm\Contact', 'id', 'contact_id');
    // }
    
    // public function soheads() {
    //     return $this->hasMany('App\Sales\Salesorder', 'custinfo_id', 'id');
    // }
    
    // public function soitems() {
    //     return $this->hasManyThrough('App\Sales\Soitem', 'App\Sales\Salesorder', 'custinfo_id', 'sohead_id');
    // }
    
    // public function receiptpayments() {
    //     return $this->hasManyThrough('App\Sales\Receiptpayments', 'App\Sales\Salesorder', 'custinfo_id', 'sohead_id');
    // }
    
    // public function sototalprice() {
    //     return '0.0';
    // }
}
