<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Receiptpayment_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use Illuminate\Console\Command;
use Log;

class ReceiptrepeatReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:receiptrepeat {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '收款重复提醒：遍历收款信息，如果同一天从同一个客户收款同样金额的信息，给出提醒消息。';

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
        $receiptpayments = Receiptpayment_hxold::select('sohead_id', 'date', 'amount')->groupBy('sohead_id', 'date', 'amount')->havingRaw('COUNT(*)>1')->get();
        $msg = '';
        $this->info(count($receiptpayments));
        if (count($receiptpayments) > 0)
        {
            $msg = '收款重复提醒: ';
            $this->sendMsg($msg, 494);      // to GuY
        }
        foreach ($receiptpayments as $receiptpayment)
        {
            $msg = $receiptpayment->sohead->projectjc . '(' . $receiptpayment->sohead->number . ')在同一天有重复收款, 日期:' . $receiptpayment->date . ', 金额:' . $receiptpayment->amount;
            $this->info($msg);
//            Log::info($receiptpayment->sohead->projectjc . '(' . $receiptpayment->sohead->number . ')在同一天有重复收款, 日期:' . $receiptpayment->date . ', 金额:' . $receiptpayment->amount);
            $this->sendMsg($msg, 494);      // to GuY
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

                DingTalkController::sendCorpMessageText(json_encode($data));
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
                    DingTalkController::sendCorpMessageText(json_encode($data));
                    sleep(1);
                }
            }
        }
    }
}
