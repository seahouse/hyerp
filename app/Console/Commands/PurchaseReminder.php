<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Issuedrawing;
use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Soheaddocs;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class PurchaseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:purchase {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采购提醒：';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 雾化器：
        // 1、筛选 喷雾 的订单
        // 2、已付预付款
        // 3、离交货日期小于10个月，
        // 提醒技术采购雾化器；当项目采购列表商品名称中有雾化器的采购订单后，取消提醒
        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
        foreach ($soheads as $sohead)
        {
            $bReminder = false;
            $receivedAmount = $sohead->receiptpayments()->sum('amount');
            $equipmenttypes = $sohead->equipmenttypes;
            foreach ($equipmenttypes as $equipmenttype)
            {
                if (strpos($equipmenttype->equipmenttype_number, '喷雾') !== false)
                {
                    $this->info($sohead->id);
//                    Log::info($sohead->id);
                    $percentSum = 0.0;
                    $paywayasses = $sohead->paywayasses;
                    foreach ($paywayasses as $paywayass)
                    {
                        $percentSum += $paywayass->paywayass_value;
                        $amountDest = $sohead->amount * $percentSum;
                        if ($paywayass->paywayass_payway_id == 1)
                        {
                            if ($receivedAmount >= $amountDest)
                            {
//                                Log::info('receivedAmount');
                                $plandeliverydate = Carbon::parse($sohead->plandeliverydate);
//                                Log::info($plandeliverydate);
                                if ($plandeliverydate->gt(Carbon::today()) && $plandeliverydate->lt(Carbon::today()->addMonths(10)))
                                {
//                                    Log::info('plandeliverydate in 10 month.');
                                    $bReminder = true;
                                    $poheads = $sohead->poheads;
//                                    Log::info($sohead->id);
                                    foreach ($poheads as $pohead)
                                    {
//                                        Log::info('productname: ' . $pohead->productname);
                                        if (strpos($pohead->productname, '雾化器') !== false)
                                        {
//                                            Log::info($pohead->productname);
                                            $bReminder = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($bReminder)
            {
                $msg = $sohead->projectjc . "(" . $sohead->number . ")已付预付款，但还未采购雾化器，请抓紧采购。";
//                Log::info($msg);
                $this->sendMsg($msg, 196);        // to LiuHM
                $this->sendMsg($msg, $sohead->salesmanager_id);
                $this->sendMsg($msg, 8);        // to WuHL
                $this->sendMsg($msg, 16);        // to LiY
                if ($sohead->designer_tech1_id > 0)
                    $this->sendMsg($msg, 268);        // to NiPP
                if ($sohead->designer_tech2_id > 0)
                    $this->sendMsg($msg, 266);        // to QiangFX
            }
        }

        // 高强螺丝
        // 下图审批的是否栓接字段，如果为1（是），则找对应的销售订单，如果它已经采购了 高强螺丝，则跳过，否则提醒购买。
        $issuedrawings = Issuedrawing::where('bolt', 1)->get();
        foreach ($issuedrawings as $issuedrawing)
        {
            $this->info($issuedrawing->id);
//            Log::info($issuedrawing);
            $sohead = $issuedrawing->sohead_hxold;
            if (isset($sohead))
            {
                $bReminder = true;
                $poheads = $sohead->poheads;
                foreach ($poheads as $pohead)
                {
                    if (strpos($pohead->productname, '高强螺丝') !== false)
                    {
//                        Log::info($pohead->productname);
                        $bReminder = false;
                        break;
                    }
                }

                if ($bReminder)
                {
                    $msg = $sohead->projectjc . "(" . $sohead->number . ")的下图审批包含栓接，但还未采购高强螺丝，请抓紧采购。";
//                    Log::info($msg);
                    $this->sendMsg($msg, 196);        // to LiuHM
                    $this->sendMsg($msg, $sohead->salesmanager_id);
                    $this->sendMsg($msg, 8);        // to WuHL
                    $this->sendMsg($msg, 16);        // to LiY
                    if ($sohead->designer_tech1_id > 0)
                        $this->sendMsg($msg, 268);        // to NiPP
                    if ($sohead->designer_tech2_id > 0)
                        $this->sendMsg($msg, 266);        // to QiangFX
                }
            }
        }

        // 刮板机 电伴热
        // 已录入开工报告
        // 过滤已经掉通烟气，或者 72，或者环保验收
        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
        foreach ($soheads as $sohead)
        {
            // 针对其中几种设备进行提醒
            $equipmenttypes = $sohead->equipmenttypes;
            foreach ($equipmenttypes as $equipmenttype)
            {
                // 6: 循环流化床法烟气净化
                // 7: 循环流化床法脱硫
                // 9: 喷雾烟气净化装置（固定喷雾、KS、希格斯）_1
                // 10: LLDM布袋除尘_1
                // 19: 喷雾烟气净化装置（尼鲁）
                // 24: 喷雾烟气净化装置（固定喷雾、KS、希格斯）_2
                // 25: LLDM布袋除尘_2
                $soheadids = [6, 7, 9, 10, 19, 24, 25];
                if (in_array($equipmenttype->id, $soheadids))
                {
                    $this->info($equipmenttype->id);
                    $soheadstartreports = Soheaddocs::where('type', 'so_kgbg')->where('sohead_id', $sohead->id)->get();
                    if ($soheadstartreports->count() > 0)
                    {
                        // 通烟气
                        $passgasDate = Carbon::parse($sohead->passgasDate);
                        $baseDate = Carbon::create(1900, 1, 1);
                        if ($passgasDate->gt($baseDate))
                            continue;

                        // 72
                        $debugendDate = Carbon::parse($sohead->debugend_date);
                        $baseDate = Carbon::create(1900, 1, 1);
                        if ($debugendDate->gt($baseDate))
                            continue;

                        // 环保验收
                        $environmentalProtectionCollectionDate = Carbon::parse($sohead->environmentalProtectionCollectionDate);
                        $baseDate = Carbon::create(1900, 1, 1);
                        if ($environmentalProtectionCollectionDate->gt($baseDate))
                            continue;

                        $bReminderGBJ = false;
                        $bReminderDBR = true;
                        $this->info($sohead->id);
                        Log::info($sohead->id);
                        // 刮板机：仅针对喷雾订单
                        $equipmenttypes = $sohead->equipmenttypes;
                        foreach ($equipmenttypes as $equipmenttype)
                        {
                            if (strpos($equipmenttype->equipmenttype_number, '喷雾') !== false)
                            {
                                $bReminderGBJ = true;

                                $poheads = $sohead->poheads;
                                foreach ($poheads as $pohead)
                                {
                                    if (strpos($pohead->productname, '刮板机') !== false)
                                    {
//                        Log::info($pohead->productname);
                                        $bReminderGBJ = false;
                                        break;
                                    }
                                }
                            }
                        }

                        $poheads = $sohead->poheads;
                        foreach ($poheads as $pohead)
                        {
                            if (strpos($pohead->productname, '电伴热') !== false)
                            {
//                        Log::info($pohead->productname);
                                $bReminderDBR = false;
                                break;
                            }
                        }
                        if ($bReminderGBJ)
                        {
                            $msg = $sohead->projectjc . "(" . $sohead->number . ")已录入开工报告，但还未采购刮板机，请抓紧采购。";
                            Log::info($msg);
                            $this->sendMsg($msg, 196);        // to LiuHM
                            $this->sendMsg($msg, $sohead->salesmanager_id);
                            $this->sendMsg($msg, 8);        // to WuHL
                            $this->sendMsg($msg, 16);        // to LiY
                            if ($sohead->designer_tech1_id > 0)
                                $this->sendMsg($msg, 268);        // to NiPP
                            if ($sohead->designer_tech2_id > 0)
                                $this->sendMsg($msg, 266);        // to QiangFX
                        }
                        if ($bReminderDBR)
                        {
                            $msg = $sohead->projectjc . "(" . $sohead->number . ")已录入开工报告，但还未采购电伴热，请抓紧采购。";
                            Log::info($msg);
                            $this->sendMsg($msg, 196);        // to LiuHM
                            $this->sendMsg($msg, $sohead->salesmanager_id);
                            $this->sendMsg($msg, 8);        // to WuHL
                            $this->sendMsg($msg, 16);        // to LiY
                            if ($sohead->designer_tech1_id > 0)
                                $this->sendMsg($msg, 268);        // to NiPP
                            if ($sohead->designer_tech2_id > 0)
                                $this->sendMsg($msg, 266);        // to QiangFX
                        }
                    }

                    break;
                }
            }


        }
    }

    public function sendMsg($msg, $userid_hxold = 0)
    {
        if ($this->option('debug'))
        {
            $touser = User::where('email', $this->argument('useremail'))->first();
            if (isset($touser))
            {
                $data = [
                    'userid'        => $touser->id,
                    'msgcontent'    => urlencode($msg) ,
                ];

//                $response = DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                $response = DingTalkController::sendActionCardMsg();
//                Log::info(json_encode($response));
                sleep(1);
            }
        }
        elseif ($userid_hxold > 0)
        {
            $transactor_hxold = Userold::where('user_hxold_id', $userid_hxold)->first();
            if (isset($transactor_hxold))
            {
                $transactor = User::where('id', $transactor_hxold->user_id)->first();
                if (isset($transactor))
                {
                    $data = [
                        'userid'        => $transactor->id,
                        'msgcontent'    => urlencode($msg) ,
                    ];
//                    Log::info($transactor->name);
                    DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                    sleep(1);
                }
            }
        }
    }
}
