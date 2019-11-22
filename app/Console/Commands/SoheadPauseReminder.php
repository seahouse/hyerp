<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class SoheadPauseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:soheadpause {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单暂停提醒：订单累计一年没有付过款，提示订单情况。对容易坏账的准备走诉讼组。';

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
        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
        $msgs = [];
        foreach ($soheads as $sohead)
        {
            $this->info($sohead->id);

            // 是否已经付款完毕
            $receivedAmount = floatval($sohead->receiptpayments()->sum('amount')) ;
            if ($sohead->amount > $receivedAmount)
            {
                $this->info($sohead->amount . "\t" . $receivedAmount);

                // 获取一年以上没有付款的订单
                $maxreceiptdate = $sohead->receiptpayments->max('date');
                if (isset($maxreceiptdate))
                {
                    $maxdate = Carbon::parse($maxreceiptdate);
                    $this->info($maxdate);
                    $today = Carbon::today();
                    if ($today->gt($maxdate->copy()->addYear(1)))
                    {
                        $remainAmount = $sohead->amount - $receivedAmount;
                        $days = $maxdate->diffInDays($today);
                        $debugend_date = Carbon::parse($sohead->debugend_date);
                        $quolityDate = Carbon::parse($sohead->quolityDate);
                        $quolity_period = $debugend_date->diffInDays($quolityDate);
                        $msg = "订单'" . $sohead->projectjc . "'最后一次收款时间为" . $maxdate->toDateString() . "，距现在已" . $days . "天，已收款" . $receivedAmount . "万元，剩余尾款" . $remainAmount . "万元，已开票金额" . $sohead->sotickets->sum('amount') . "万元。质保期：" . $quolity_period . "天。\n付款方式：" . $sohead->paymethod;
//                        Log::info($msg);
                        $msgs[$sohead->id]['days'] = $days;
                        $msgs[$sohead->id]['msg'] = $msg;
                        $this->sendMsg($msg, $sohead->salesmanager_id);
                    }
                }
            }
        }
        $msgs = array_sort($msgs, function ($value) {
            return 0 - $value['days'];
        });

        foreach ($msgs as $value)
        {
            $this->sendMsg($value['msg'], 8);        // to WuHL
            $this->sendMsg($value['msg'], 495);      // to WangGZ
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

                $response = DingTalkController::sendCorpMessageTextReminder(json_encode($data));
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
