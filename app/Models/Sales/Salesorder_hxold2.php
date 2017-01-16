<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Salesorder_hxold2 extends Model
{
    protected $table = 'vorder2';
	protected $connection = 'sqlsrv';
    
    //
    // protected $fillable = [
    //     'number',
    //     'descrip',
    //     'custinfo_id',
    //     'orderdate',
    //     'warehouse_id',
    //     'shipto',
    //     'salesrep_id',
    //     'term_id',
    //     'comments',
    // ];
    
    // public function custinfo() {
    //     return $this->hasOne('App\Models\Sales\Custinfo_hxold', 'id', 'custinfo_id');
    // }
    
    // public function warehouse() {
    //     return $this->hasOne('App\Inventory\Warehouse', 'id', 'warehouse_id');
    // }
    
    // public function salesrep() {
    //     return $this->hasOne('App\Models\Sales\Salesrep', 'id', 'salesrep_id');
    // }
    
    // public function term() {
    //     return $this->hasOne('App\Sales\Term', 'id', 'term_id');
    // }
    
    // public function soitems() {
    //     return $this->hasMany('App\Models\Sales\Soitem', 'sohead_id');
    // }
}
