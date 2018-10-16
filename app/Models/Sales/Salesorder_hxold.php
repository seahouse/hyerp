<?php

namespace App\Models\Sales;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB, Log;

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

    public function poheads_simple() {
        return $this->hasMany('App\Models\Purchase\Purchaseorder_hxold_simple', 'sohead_id', 'id');
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

    public function equipmenttypes() {
        return $this->belongsToMany('App\Models\Sales\Equipmenttype_hxold', 'equipmenttypeass', 'equipmenttypeass_order_id', 'equipmenttypeass_equipmenttype_id');
    }

    // 奖金系数/折扣 如果没有字段值，则根据政策动态生成，不取字段值
    public function getBonusfactorByPolicy($date = '') {
        if (strlen($date) > 0)
        {
            $bonusfactor_hxold = Bonusfactor_hxold::where('sohead_id', $this->id)->where('date', Carbon::parse($date))->first();
            if ($bonusfactor_hxold)
                return $bonusfactor_hxold->value;
        }
        if ($this->bonusfactor > 0.0)
            return $this->bonusfactor;
        
        $bonusfactor = 0.0;
        foreach ($this->equipmenttypes as $equipmenttype)
        {
            if (isset($equipmenttype))
            {
                $bonusfactortemp = 0.0;
                switch ($equipmenttype->id)
                {
                    case 1:     // 1
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 2:     // 2
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 4:     // 3
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.8, 1.2);
                        break;
                    case 11:     // 4
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.8, 1.2);
                        break;
                    case 9:     // 5
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 10:     // 6
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 17:     // 7
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 18:     // 8
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 6:     // 9
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 7:     // 10
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 19:     // 11
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 5:     // 12
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 20:     // 13
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 21:     // 14
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 22:     // 15
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.8, 1.2);
                        break;
                    case 23:     // 16
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.8, 1.2);
                        break;
                    case 24:     // 17
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 25:     // 18
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 13:     // 19
                        $bonusfactortemp = $this->getBonusfactorByReceiptpaymentPercent(0.5, 0.8);
                        break;
                    case 16:     // 20
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 26:     // 21
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 27:     // 22
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 14:     // 23
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                    case 12:     // 24
                        $bonusfactortemp = $this->getBonusfactorByAmount();
                        break;
                }
                if ($bonusfactor == 0.0 || $bonusfactor > $bonusfactortemp)
                    $bonusfactor = $bonusfactortemp;
            }
        }
//        $equipmenttype = $this->equipmenttypes->first();

//        dd($this->equipmenttypes->first());
        return $bonusfactor;
    }

    private function getBonusfactorByAmount() {
        $bonusfactor = 0.0;
        if ($this->amount < 1000.0)
            $bonusfactor = $this->getBonusfactorByReceiptpaymentPercent(0.2, 0.35);
        elseif ($this->amount >= 1000.0 && $this->amount < 2000.0)
            $bonusfactor = $this->getBonusfactorByReceiptpaymentPercent(0.15, 0.35);
        elseif ($this->amount >= 2000.0)
            $bonusfactor = $this->getBonusfactorByReceiptpaymentPercent(0.1, 0.35);
        return $bonusfactor;
    }

    private function getBonusfactorByReceiptpaymentPercent($mixbonusfactor, $maxbonusfactor)
    {
        $bonusfactor = $mixbonusfactor;
        $offset = ($maxbonusfactor - $mixbonusfactor) / 4;
        if ($this->amount > 0.0)
        {
            $amount = $this->amount;
            $poheadamounttotal = $this->poheads_simple->sum('amount') / 10000.0;
            $receiptpaymenttotal = $this->receiptpayments->sum('amount');
            $poheadamounpercent = $poheadamounttotal / $amount;
            if ($receiptpaymenttotal / $amount >= 0.6)
            {
                if ($poheadamounpercent >= 0.5 && $poheadamounpercent < 0.6)
                    $bonusfactor = $bonusfactor - $offset;
                elseif ($poheadamounpercent >= 0.6 && $poheadamounpercent < 0.7)
                    $bonusfactor = $bonusfactor - $offset * 2;
                elseif ($poheadamounpercent >= 0.7 && $poheadamounpercent < 0.8)
                    $bonusfactor = $bonusfactor - $offset * 3;
                elseif ($poheadamounpercent / $amount >= 0.8)
                    $bonusfactor = $mixbonusfactor;
            }
        }
        return $bonusfactor;
    }

    // 奖金比例，根据政策获取，不取字段值
    public function getAmountpertenthousandBySohead() {
//        return 250;
        return DB::connection('sqlsrv')->select('select dbo.getAmountpertenthousandBySohead(' . $this->id . ') as amountpertenthousandbysohead');
    }

    public function bonuspayments() {
        return $this->hasMany('App\Models\Sales\Bonuspayment_hxold', 'sohead_id', 'id');
    }

    public function senddetails() {
        return $this->hasMany('App\Models\Sales\Senddetail_hxold', 'sohead_id', 'id');
    }
}
