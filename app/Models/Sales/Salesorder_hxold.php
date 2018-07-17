<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use DB;

class Salesorder_hxold extends Model
{
    protected $table = 'vorder';
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
    
    public function custinfo() {
        return $this->hasOne('App\Models\Sales\Custinfo_hxold', 'id', 'custinfo_id');
    }
    
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

    public function poheads() {
        return $this->hasMany('App\Models\Purchase\Purchaseorder_hxold', 'sohead_id', 'id');
    }

    public function receiptpayments() {
        return $this->hasMany('App\Models\Sales\Receiptpayment_hxold', 'sohead_id', 'id');
    }

    // 此订单的对应的采购订单的对应的付款记录
    public function payments() {
        return $this->hasManyThrough('App\Models\Purchase\Payment_hxold', 'App\Models\Purchase\Purchaseorder_hxold', 'sohead_id', 'pohead_id');
    }

    // 公用订单的分摊成本金额
    public function getPoheadAmountBy7550() {
        return DB::connection('sqlsrv')->select('select dbo.getPoheadAmountBy7550(' . $this->id . ') as poheadAmountBy7550');
    }

    public function soheadtaxratetypeasses() {
        return $this->hasMany('App\Models\Sales\Soheadtaxratetypeass_hxold', 'sohead_id', 'id');
    }

    // 税率差
    public function temTaxamountstatistics() {
        return $this->hasOne('App\Models\Sales\Tem_Taxamountstatistics_hxold', 'sohead_id', 'id');
    }

    public function project() {
        return $this->hasOne('App\Models\Sales\Project_hxold', 'id', 'project_id');
    }
}
