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

    // 公用订单的分摊成本金额
    public function getPoheadTaxAmountBy7550() {
        return DB::connection('sqlsrv')->select('select dbo.getPoheadTaxAmountBy7550(' . $this->id . ') as poheadTaxAmountBy7550');
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

    public function paywayasses() {
        return $this->hasMany('App\Models\Sales\Paywayass_hxold', 'paywayass_order_id');
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
        if ($this->type == 0)       // 普通订单
        {
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
        }
        elseif ($this->type == 1)       // 配件订单
        {
            $bonusfactor = $this->getBonusfactorByReceiptpaymentPercent2(0.2, 2);
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
            $project = $this->project;
            if (isset($project))
            {
                $amount = 0.0;
                $poheadamounttotal = 0.0;
                $poheadAmountBy7550 = 0.0;
                $sohead_taxamount = 0.0;
                $sohead_poheadtaxamount = 0.0;
                $receiptpaymenttotal = 0.0;
                foreach ($project->soheads as $sohead)
                {
                    $amount += $sohead->amount;
                    $poheadamounttotal += $sohead->poheads->sum('amount');
                    $poheadAmountBy7550 += array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550;
                    $sohead_taxamount += isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0;
                    $sohead_poheadtaxamount += isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
                    $receiptpaymenttotal += $sohead->receiptpayments->sum('amount');
                }
                $poheadcostpercent = ($poheadamounttotal + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount) / ($amount * 10000.0);
//            $poheadamounpercent = $poheadamounttotal / $amount;
                if ($receiptpaymenttotal / $amount >= 0.6)
                {
                    if (abs($maxbonusfactor - 0.35) < 0.000001 && abs($mixbonusfactor - 0.1) < 0.000001)
                    {
                        if ($poheadcostpercent < 0.65)
                            $bonusfactor = 0.35;
                        elseif ($poheadcostpercent >= 0.65 && $poheadcostpercent < 0.7)
                            $bonusfactor = 0.3;
                        elseif ($poheadcostpercent >= 0.7 && $poheadcostpercent < 0.75)
                            $bonusfactor = 0.25;
                        elseif ($poheadcostpercent >= 0.75 && $poheadcostpercent < 0.8)
                            $bonusfactor = 0.2;
                        elseif ($poheadcostpercent >= 0.8 && $poheadcostpercent < 0.85)
                            $bonusfactor = 0.15;
                        else
                            $bonusfactor = 0.1;
                    }
                    else
                    {
                        $bonusfactor = $maxbonusfactor;
                        if ($poheadcostpercent >= 0.5 && $poheadcostpercent < 0.6)
                            $bonusfactor = $bonusfactor - $offset;
                        elseif ($poheadcostpercent >= 0.6 && $poheadcostpercent < 0.7)
                            $bonusfactor = $bonusfactor - $offset * 2;
                        elseif ($poheadcostpercent >= 0.7 && $poheadcostpercent < 0.8)
                            $bonusfactor = $bonusfactor - $offset * 3;
                        elseif ($poheadcostpercent >= 0.8)
                            $bonusfactor = $mixbonusfactor;
                    }
                }
            }

//            $amount = $this->amount;
//            $poheadamounttotal = $this->poheads_simple->sum('amount');
//            $poheadAmountBy7550 = array_first($this->getPoheadAmountBy7550())->poheadAmountBy7550;
//            $sohead_taxamount = isset($this->temTaxamountstatistics->sohead_taxamount) ? $this->temTaxamountstatistics->sohead_taxamount : 0.0;
//            $sohead_poheadtaxamount = isset($this->temTaxamountstatistics->sohead_poheadtaxamount) ? $this->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
//            $poheadcostpercent = ($poheadamounttotal + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount) / ($amount * 10000.0);
////            $poheadamounpercent = $poheadamounttotal / $amount;
//            $receiptpaymenttotal = $this->receiptpayments->sum('amount');
//            if ($receiptpaymenttotal / $amount >= 0.6)
//            {
//                $bonusfactor = $maxbonusfactor;
//                if ($poheadcostpercent >= 0.5 && $poheadcostpercent < 0.6)
//                    $bonusfactor = $bonusfactor - $offset;
//                elseif ($poheadcostpercent >= 0.6 && $poheadcostpercent < 0.7)
//                    $bonusfactor = $bonusfactor - $offset * 2;
//                elseif ($poheadcostpercent >= 0.7 && $poheadcostpercent < 0.8)
//                    $bonusfactor = $bonusfactor - $offset * 3;
//                elseif ($poheadcostpercent / $amount >= 0.8)
//                    $bonusfactor = $mixbonusfactor;
//            }
        }
        return $bonusfactor;
    }

    // 针对配件订单
    private function getBonusfactorByReceiptpaymentPercent2($mixbonusfactor, $maxbonusfactor)
    {
        $bonusfactor = $mixbonusfactor;
        $offset = ($maxbonusfactor - $mixbonusfactor) / 6;
        if ($this->amount > 0.0)
        {
            $amount = $this->amount;
            $poheadamounttotal = $this->poheads_simple->sum('amount') / 10000.0;
            $poheadAmountBy7550 = array_first($this->getPoheadAmountBy7550())->poheadAmountBy7550;
            $sohead_taxamount = isset($this->temTaxamountstatistics->sohead_taxamount) ? $this->temTaxamountstatistics->sohead_taxamount : 0.0;
            $sohead_poheadtaxamount = isset($this->temTaxamountstatistics->sohead_poheadtaxamount) ? $this->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
            $poheadcostpercent = ($poheadamounttotal + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount) / ($amount * 10000.0);
//            $poheadamounpercent = $poheadamounttotal / $amount;
            $receiptpaymenttotal = $this->receiptpayments->sum('amount');
            if ($receiptpaymenttotal / $amount >= 0.6)
            {
                $bonusfactor = $maxbonusfactor;
                if ($poheadcostpercent >= 0.3 && $poheadcostpercent < 0.4)
                    $bonusfactor = $bonusfactor - $offset;
                elseif ($poheadcostpercent >= 0.4 && $poheadcostpercent < 0.5)
                    $bonusfactor = $bonusfactor - $offset * 2;
                elseif ($poheadcostpercent >= 0.5 && $poheadcostpercent < 0.6)
                    $bonusfactor = $bonusfactor - $offset * 3;
                elseif ($poheadcostpercent >= 0.6 && $poheadcostpercent < 0.7)
                    $bonusfactor = $bonusfactor - $offset * 4;
                elseif ($poheadcostpercent >= 0.7 && $poheadcostpercent < 0.8)
                    $bonusfactor = $bonusfactor - $offset * 5;
                elseif ($poheadcostpercent >= 0.8)
                    $bonusfactor = $mixbonusfactor;
            }
        }
        return $bonusfactor;
    }

    // 奖金比例，根据政策获取，不取字段值
    public function getAmountpertenthousandBySohead() {
//        return 250;
        if ($this->type == 0)   // 普通订单
            return DB::connection('sqlsrv')->select('select dbo.getAmountpertenthousandBySohead(' . $this->id . ') as amountpertenthousandbysohead');
        else       // 配件订单
            return DB::connection('sqlsrv')->select('select 300 as amountpertenthousandbysohead');
    }

    // 技术部人员奖金系数
    public function getAmountpertenthousandBySoheadTech() {
        return 25;
    }

    public function bonuspayments() {
        return $this->hasMany('App\Models\Sales\Bonuspayment_hxold', 'sohead_id', 'id');
    }

    public function senddetails() {
        return $this->hasMany('App\Models\Sales\Senddetail_hxold', 'sohead_id', 'id');
    }

    public function sotickets() {
        return $this->hasMany('App\Models\Sales\Soticket_hxold', 'sohead_id', 'id');
    }

    public function soheaddocs() {
        return $this->hasMany('App\Models\Sales\Soheaddocs', 'sohead_id', 'id');
    }

    // 是否需要收款：根据约定的付款方式判断是否需要收款
    public function needreceiptpayment() {
        $soheadAmount = $this->amount;
        $receivedAmount = $this->receiptpayments()->sum('amount');
        $msgList = [];
        $notReceiveAmountForWarning = 0.0;
        // 获取付款方式
        $paywayasses = Paywayass_hxold::where('paywayass_order_id', $this->id)->orderBy('payway_seq')->get();
        $percentSum = 0.0;
        $bWarning = false;
        foreach ($paywayasses as $paywayass)
        {
            $paywayId = $paywayass->paywayass_payway_id;
            $percentSum += $paywayass->paywayass_value;
//                Log::info("percentSum: " . $percentSum);

            $amountDest = $this->amount * $percentSum;
            $notReceivedAmount = $amountDest - $receivedAmount;
//                Log::info("notReceivedAmount: " . $notReceivedAmount);

            switch ($paywayId)
            {
                case 1:         // 预付款: 合同签订后10天
                    $orderDate = Carbon::parse($this->orderdate);
                    if (Carbon::now()->gt($orderDate->addDay(10)))
                        $bWarning = true;
                    break;
                case 2:         // 提资款: 合同签订后20天
                    $orderDate = Carbon::parse($this->orderdate);
                    if (Carbon::now()->gt($orderDate->addDay(20)))
                        $bWarning = true;
                    break;
                case 15:        // 设计结束款: 技术部与电气部都已完成
                    // 目前数据库中的电气部似乎都没有显示完成，所以先不考虑电气部的完成情况
                    if ($this->techdept_status == 1 && $this->elecdept_status == 1)
//                        if ($this->techdept_status == 1)
                    {
                        $bWarning = true;
                    }
                    break;
                case 3:         // 进度款: 已填写开工日期
                case 4:         // 发货前款: 已填写开工日期
                    $startDate = Carbon::parse($this->startDate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($startDate->gt($baseDate))
                        $bWarning = true;
                    break;
                case 13:        // 部分到货款: 已勾选发货
                case 5:         // 全部到货款: 已勾选发货
                    if ($this->delivery_status == 1)
                    {
                        $bWarning = true;
                    }
                    break;
                case 14:            // 安装结束款: 已填写安装日期
                    $installeddate = Carbon::parse($this->installeddate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($installeddate->gt($baseDate))
                    {
                        $bWarning = true;
                    }
                    break;
                case 7:             // 调试后: 已填写项目投运日期（72+24小时完成日）
                    // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                    $debugendDate = Carbon::parse($this->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($debugendDate->gt($baseDate))
                    {
                        $bWarning = true;
                    }
                    break;
                case 23:            // 通烟气款: 已填写通烟气日期
                    $passgasDate = Carbon::parse($this->passgasDate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($passgasDate->gt($baseDate))
                        $bWarning = true;
                    break;
                case 21:            // 滤料质保金: 已填写通烟气日期后两年
                    $passgasDate = Carbon::parse($this->passgasDate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($passgasDate->gt($baseDate) && Carbon::now()->gt($passgasDate->addYear(2)))
                        $bWarning = true;
                    break;
                case 8:             // 72小时后款: 已填写项目投运日期
                    // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                    $debugendDate = Carbon::parse($this->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($debugendDate->gt($baseDate))
                    {
                        $bWarning = true;
                    }
                    break;
                case 22:            // 性能验收后: 已填写性能验收日期
                    $performanceAcceptDate = Carbon::parse($this->performanceAcceptDate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($performanceAcceptDate->gt($baseDate))
                        $bWarning = true;
                    break;
                case 17:            // 运行3个月: 已填写已填写项目投运日期后3个月
                    $debugendDate = Carbon::parse($this->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($debugendDate->gt($baseDate) && Carbon::now()->gt($debugendDate->addMonth(3)))
                        $bWarning = true;
                    break;
                case 18:            // 运行半年: 已填写已填写项目投运日期后6个月
                    $debugendDate = Carbon::parse($this->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($debugendDate->gt($baseDate) && Carbon::now()->gt($debugendDate->addMonth(6)))
                        $bWarning = true;
                    break;
                case 9:             // 运行1年
                    // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                    $debugendDate = Carbon::parse($this->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($debugendDate->gt($baseDate) && Carbon::now()->gt($debugendDate->addMonth(12)))
                    {
                        $bWarning = true;
                    }
                    break;
                case 12:            // 环保验收: 已填写环保验收日期
                    // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                    $environmentalProtectionCollectionDate = Carbon::parse($this->environmentalProtectionCollectionDate);
                    $baseDate = Carbon::create(1900, 1, 1);
                    if ($environmentalProtectionCollectionDate->gt($baseDate) && Carbon::now()->gt($environmentalProtectionCollectionDate))
                    {
                        $bWarning = true;
                    }
                    break;
                case 10:        // 质保金: 质保金到期日期.  不知道为什么质保金日期在后台里很多订单是 1901-01-01, 而不是 1900
                    $quolityDate = Carbon::parse($this->quolityDate);
                    $baseDate = Carbon::create(1901, 1, 1);
                    if ($quolityDate->gt($baseDate) && Carbon::now()->gt($quolityDate))
                    {
                        $bWarning = true;
                    }
                    break;
                case 19:        // 第1年质保金: quolityDate
                    $quolityDate = Carbon::parse($this->quolityDate);
                    $baseDate = Carbon::create(1901, 1, 1);
                    if ($quolityDate->gt($baseDate) && Carbon::now()->gt($quolityDate->addYear(1)))
                    {
                        $bWarning = true;
                    }
                    break;
                case 20:        // 第2年质保金: quolityDate
                    $quolityDate = Carbon::parse($this->quolityDate);
                    $baseDate = Carbon::create(1901, 1, 1);
                    if ($quolityDate->gt($baseDate) && Carbon::now()->gt($quolityDate->addYear(2)))
                    {
                        $bWarning = true;
                    }
                    break;
                case 11:
                    break;
                default:
                    break;
            }

            if ($bWarning)
            {
                if ($notReceivedAmount > $this->amount * 0.01)
                    ;
                else
                    $bWarning = false;
//                $notReceiveAmountForWarning = $notReceivedAmount;
//                $msgTemp = "应收" . $paywayass->payway_name . "款" . $amountDest . "万, " .
//                    "实收" . $receivedAmount . "万, 未收" . $notReceivedAmount . "万";
//                $msgTemp = "累计可收" . $amountDest . "万(" . $amountDest / $soheadAmount * 100.0 . "%), " .
//                    "累计实收" . doubleval($receivedAmount) . "万(" . number_format($receivedAmount / $soheadAmount * 100.0, 2) . "%), " .
//                    "差" . $notReceivedAmount . "万(" . number_format($notReceivedAmount / $soheadAmount * 100.0, 2) . "%).";
//                array_push($msgList, $msgTemp);
            }
        }

//        if (count($msgList) > 0)
//        {
//
//            $msg = ($this->projectjc == "" ? $this->descrip : $this->projectjc) . ", "  .
//                "合同" . doubleval($soheadAmount) . "万, " . array_pop($msgList) . " \n付款方式: " . $this->paymethod;
//
//            $salesmanager_id = $this->salesmanager_id;
//            if (!array_key_exists($this->salesmanager_id, $receiptPeopleArray))
//            {
//                $receiptPeopleArray[$salesmanager_id] = [];
//                $receiptPeopleArray[$salesmanager_id]['msg'] = [];
//                $receiptPeopleArray[$salesmanager_id]['total'] = 0.0;
//            }
//            array_push($receiptPeopleArray[$this->salesmanager_id]['msg'], ($this->projectjc == "" ? $this->descrip : $this->projectjc) . $notReceiveAmountForWarning . "万元")  ;
//            $receiptPeopleArray[$salesmanager_id]['total'] += $notReceiveAmountForWarning;
//
//            // 向销售经理发送消息
//            $this->sendMsg($msg, $this->salesmanager_id);
//            $this->sendMsg($msg, 8);        // to WuHL
//            $this->sendMsg($msg, 16);       // to LiY
//        }
        return $bWarning;
    }

    // 集团里所有的订单都垫资
    public function groupdianziallsohead() {
        $bRtn = true;
        $group = $this->project->group;
        if (isset($group))
        {
//            Log::info('Group:' . $group->id);
            foreach ($group->projects as $project)
            {
                foreach ($project->soheads as $sohead)
                {
//                    Log::info('Sohead:' . $sohead->id);
//                    Log::info($sohead->receiptpayments->sum('amount') * 10000);
//                    Log::info($sohead->payments->sum('amount'));
                    if ($sohead->receiptpayments->sum('amount') * 10000 >= $sohead->payments->sum('amount'))
                    {
                        $bRtn = false;
                        break;
                    }
                }
            }
        }
        return $bRtn;
    }
}
