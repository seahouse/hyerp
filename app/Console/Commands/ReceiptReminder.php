<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Paywayass_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class ReceiptReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:receipt {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '收款提醒：根据收款节点向相关人员（销售经理）发送收款提醒消息。';

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
        //
        $soheads = Salesorder_hxold::all();
        $receiptPeopleArray = [];
        foreach ($soheads as $sohead)
        {
            $this->info($sohead->id);
//            if ($sohead->id != 7527 && $sohead->id != 7538)
//                continue;

            $soheadAmount = $sohead->amount;
            $receivedAmount = $sohead->receiptpayments()->sum('amount');
            $msgList = [];
            $toWuHL = false;
            // 获取付款方式
            $paywayasses = Paywayass_hxold::where('paywayass_order_id', $sohead->id)->orderBy('payway_seq')->get();
            $percentSum = 0.0;
            foreach ($paywayasses as $paywayass)
            {
                $this->info('  ' . $paywayass->paywayass_id);
                $paywayId = $paywayass->paywayass_payway_id;
                $percentSum += $paywayass->paywayass_value;
//                Log::info("percentSum: " . $percentSum);

                $bWarning = false;
                $amountDest = $sohead->amount * $percentSum;
                $notReceivedAmount = $amountDest - $receivedAmount;
//                Log::info("notReceivedAmount: " . $notReceivedAmount);

                $this->info('    ' . $paywayId);
                switch ($paywayId)
                {
                    case 2:
                    case 3:
                    case 4:
                        // 目前数据库中的电气部似乎都没有显示完成，所以先不考虑电气部的完成情况
//                        if ($sohead->techdept_status == 1 && $sohead->elecdept_status == "完成")
                        if ($sohead->techdept_status == 1)
                        {
                            $bWarning = true;
                            $this->info('      ' . "design finished.");
                        }
                        break;
                    case 13:
                    case 5:
                        if ($sohead->delivery_status == 1)
                        {
                            $bWarning = true;
//                            $this->info('      ' . "delivery finished.");
//                            Log::info("delivery finished.");
                        }
                        break;
                    case 14:
                    case 7:             // 调试后: 项目投运日期（72+24小时完成日）
                    case 8:
                        // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                        $this->info('      ' . $sohead->debugend_date);
                        $debugendDate = Carbon::parse($sohead->debugend_date);
                        $this->info('      ' . $debugendDate);
                        $baseDate = Carbon::create(1900, 1, 1);
                        if ($sohead->id == 7538)
                        {
//                            Log::info($sohead->debugend_date);
//                            Log::info($debugendDate);
//                            Log::info($baseDate);
//                            Log::info($amountDest);
//                            Log::info($receivedAmount);
//                            Log::info($notReceivedAmount);
                        }
                        if ($debugendDate->gt($baseDate))
                        {
                            $bWarning = true;
//                            $this->info('      ' . "debug finished.");
//                            Log::info("debug finished.");
                        }
                        break;
                    case 9:             //
                    case 12:            // 环保验收: 项目投运日期（72+24小时完成日）后, 6个月后
                    case 11:
                        // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                        $this->info('      ' . $sohead->debugend_date);
                        $debugendDate = Carbon::parse($sohead->debugend_date);
                        $this->info('      ' . $debugendDate);
                        $baseDate = Carbon::create(1900, 1, 1);
                        if ($sohead->id == 7545)
                        {
                            Log::info($sohead->debugend_date);
                            Log::info($debugendDate);
                            Log::info($baseDate);
                        }
                        if ($debugendDate->gt($baseDate) && Carbon::now()->gt($debugendDate->addMonth(6)))
                        {
                            $bWarning = true;
                        }
                        break;
                    case 10:        // 质保: 项目投运日期（72+24小时完成日）后, 12个月后
                        // Carbon使用方法: https://9iphp.com/web/laravel/php-datetime-package-carbon.html
                        $this->info('      ' . $sohead->debugend_date);
                        $debugendDate = Carbon::parse($sohead->debugend_date);
                        $this->info('      ' . $debugendDate);
                        $baseDate = Carbon::create(1900, 1, 1);
//                        if ($sohead->id == 7545)
//                        {
//                            Log::info($sohead->debugend_date);
//                            Log::info($debugendDate);
//                            Log::info($baseDate);
//                        }
                        if ($debugendDate->gt($baseDate) && Carbon::now()->gt($debugendDate->addMonth(12)))
                        {
                            $bWarning = true;
//                            $this->info('      ' . "debug finished.");
//                            Log::info("debug finished.");
                        }
                        break;
                    default:
                        ;
                }

                if ($bWarning && $notReceivedAmount > $sohead->amount * 0.01)
                {
                    $msgTemp = "应收" . $paywayass->payway_name . "款" . $amountDest . "万, " .
                        "实收" . $receivedAmount . "万, 未收" . $notReceivedAmount . "万";
//                    Log::info($msgTemp);
                    $msgTemp = "累计可收" . $amountDest . "万(" . $amountDest / $soheadAmount * 100.0 . "%), " .
                        "累计实收" . doubleval($receivedAmount) . "万(" . number_format($receivedAmount / $soheadAmount * 100.0, 2) . "%), " .
                        "差" . $notReceivedAmount . "万(" . number_format($notReceivedAmount / $soheadAmount * 100.0, 2) . "%).";
//                    if ($notReceivedAmount > 50.0)
                        $toWuHL = true;
                    array_push($msgList, $msgTemp);
                }
            }

            if (count($msgList) > 0)
            {
//                $msg = ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc) . ", "  .
//                    "合同" . $sohead->amount . "万, 于" . $sohead->orderdate . "签订. " .
//                    implode(',', $msgList) .
//                    ", 请抓紧催收. 1";
//                $msg = ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc) . ", "  .
//                    "合同" . $sohead->amount . "万, 累计可收" . $amountDest . "万, 累计实收" . $receivedAmount .
//                    "万, 差" . $notReceivedAmount . "万. 2";

                $msg = ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc) . ", "  .
                    "合同" . doubleval($soheadAmount) . "万, " . array_pop($msgList) . " \n付款方式: " . $sohead->paymethod;
//                $msg = ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc) . ", "  .
//                    "合同" . $sohead->amount . "万, " . implode(',', $msgList) . "";

//                Log::info($msg);

                // 本地测试
                if ($this->option('debug'))
                {
                    $touser = User::where('email', $this->argument('useremail'))->first();
                    if (isset($touser))
                    {
                        DingTalkController::send($touser->dtuserid, '',
                            $msg,
                            config('custom.dingtalk.agentidlist.erpmessage'));

                        $salesmanager_id = $sohead->salesmanager_id;
                        if (!array_key_exists($sohead->salesmanager_id, $receiptPeopleArray))
                        {
                            $receiptPeopleArray[$salesmanager_id] = [];
                            $receiptPeopleArray[$salesmanager_id]['msg'] = [];
                            $receiptPeopleArray[$salesmanager_id]['total'] = 0.0;
                        }
                        array_push($receiptPeopleArray[$sohead->salesmanager_id]['msg'], ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc) . $notReceivedAmount . "万元")  ;
                        $receiptPeopleArray[$salesmanager_id]['total'] += $notReceivedAmount;
                    }
                }
                else
                {
                    // 向销售经理发送消息
                    $salesmanager_hxold = Userold::where('user_hxold_id', $sohead->salesmanager_id)->first();
                    if (isset($salesmanager_hxold))
                    {
                        $salesmanager = User::where('id', $salesmanager_hxold->user_id)->first();
                        if (isset($salesmanager))
                            DingTalkController::send($salesmanager->dtuserid, '',
                                $msg,
                                config('custom.dingtalk.agentidlist.erpmessage'));
                    }

                    // 向吴HL发送消息
                    if ($toWuHL)
                    {
                        $touser = User::where('email', 'wuhaolun@huaxing-east.com')->first();
                        if (isset($touser))
                            DingTalkController::send($touser->dtuserid, '',
                                $msg,
                                config('custom.dingtalk.agentidlist.erpmessage'));
                    }
                }

            }
        }
        Log::info(json_encode($receiptPeopleArray));

        foreach ( $receiptPeopleArray as $key => $value)
        {
            Log::info($key . implode(", ", $value['msg']) . $value['total']);

            // 向销售经理发送消息
            $salesmanager_hxold = Userold::where('user_hxold_id', $key)->first();
            if (isset($salesmanager_hxold))
            {
                $salesmanager = User::where('id', $salesmanager_hxold->user_id)->first();
                if (isset($salesmanager))
                {
                    $msg = $salesmanager->name . "可收" . $value['total'] . "万元, 明细: " . implode(", ", $value['msg']) . ".";
                    
                    if ($this->option('debug'))
                    {
                        $touser = User::where('email', $this->argument('useremail'))->first();
                        if (isset($touser)) {
                            DingTalkController::send($touser->dtuserid, '',
                                $msg,
                                config('custom.dingtalk.agentidlist.erpmessage'));
                        }
                    }
                    else
                        DingTalkController::send($salesmanager->dtuserid, '',
                            $msg,
                            config('custom.dingtalk.agentidlist.erpmessage'));

//                    Log::info($salesmanager->name . "可收" . $value['total'] . "万元, 明细: " . implode(", ", $value['msg']) . ".");
                }

            }
        }

//        DingTalkController::send('manager1200', '',
//            '来自的付款单需要您审批.',
//            config('custom.dingtalk.agentidlist.approval'));
    }
}
