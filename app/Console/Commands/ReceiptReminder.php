<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Paywayass_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
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
    protected $signature = 'reminder:receipt {useremail=admin@admin.com}';

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
        foreach ($soheads as $sohead)
        {
            $this->info($sohead->id);
            $receivedAmount = $sohead->receiptpayments()->sum('amount');
            $msgList = [];
            // 获取付款方式
            $paywayasses = Paywayass_hxold::where('paywayass_order_id', $sohead->id)->get();
            $percentSum = 0.0;
            foreach ($paywayasses as $paywayass)
            {
                $this->info('  ' . $paywayass->paywayass_id);
                $paywayId = $paywayass->paywayass_payway_id;
                $percentSum += $paywayass->paywayass_value;

                $amountDest = $sohead->amount * $percentSum;
                $notReceivedAmount = $amountDest - $receivedAmount;
//                Log::info("notReceivedAmount: " . $notReceivedAmount);

                $this->info('    ' . $paywayId);
                switch ($paywayId)
                {
                    case 2:
                    case 3:
                        // 目前数据库中的电气部似乎都没有显示完成，所以先不考虑电气部的完成情况
//                        if ($sohead->techdept_status == 1 && $sohead->elecdept_status == "完成")
                        if ($sohead->techdept_status == 1)
                        {
                            $this->info('      ' . "design finished.");
                        }
                        break;
                    case 5:
                        if ($sohead->delivery_status == 1)
                        {
                            $this->info('      ' . "delivery finished.");
                            Log::info("delivery finished.");
                        }
                        break;
                    case 7:
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
                        if ($debugendDate->gt($baseDate))
                        {
                            $this->info('      ' . "debug finished.");
                            Log::info("debug finished.");
                        }
                        break;
                    default:
                        ;
                }

                if ($notReceivedAmount > 0.0)
                {
                    $msgTemp = "应收" . $paywayass->payway_name . "款" . $amountDest . "万, " .
                        "实收" . $receivedAmount . "万, 未收" . $notReceivedAmount . "万";
                    Log::info($msgTemp);
                    array_push($msgList, $msgTemp);
                }
            }

            if (count($msgList) > 0)
            {
                $msg = "客户为" . $sohead->custinfo_name . "的" . $sohead->descrip . "项目, " .
                    "该订单金额为" . $sohead->amount . "万, 于" . $sohead->orderdate . "签订. " .
                    implode(',', $msgList) .
                    ", 请抓紧催收";

                Log::info($msg);

                $touser = User::where('email', $this->argument('useremail'))->first();
                if (isset($touser))
                    DingTalkController::send($touser->dtuserid, '',
                        $msg,
                        config('custom.dingtalk.agentidlist.approval'));

            }
        }
//        DingTalkController::send('manager1200', '',
//            '来自的付款单需要您审批.',
//            config('custom.dingtalk.agentidlist.approval'));
    }
}
