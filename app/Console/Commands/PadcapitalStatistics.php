<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Vorderamountstatistics_hxold;
use App\Models\System\User;
use Illuminate\Console\Command;
use DB, Log;

class PadcapitalStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:padcapital {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '垫资统计: 对垫资金额进行统计, 并发送给Wuhl';

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

        $padcapitalTotal = 0.0;
        $orderamountstatisticses = Vorderamountstatistics_hxold::where('padcapital', '>', 0.0)->orderBy('padcapital', 'desc')->get();
        foreach ($orderamountstatisticses as $orderamountstatistics)
        {
            $padcapitalTotal += $orderamountstatistics->padcapital;
            $msg = $orderamountstatistics->projectjc == "" ? $orderamountstatistics->descrip : $orderamountstatistics->projectjc
                . '(' .$orderamountstatistics->number . ')垫资'
                . number_format($orderamountstatistics->padcapital, 2, '.', ',') . '元.';
            $this->info('  ' . $msg);
            if ($this->option('debug'))
            {
                $touser = User::where('email', $this->argument('useremail'))->first();
                if (isset($touser))
                {
                    $this->info('  ' . $touser->id);
                    $data = [
                        'userid'        => $touser->id,
                        'msgcontent'    => urlencode($msg) ,
                    ];

                    $response = $response = DingTalkController::sendCorpMessageText(json_encode($data));
                    Log::info($response);
                    sleep(1);
                }
            }
            else
            {
                // send msg to Wuhl
                $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
                if (isset($userWuhl))
                {
                    $data = [
                        'userid'        => $userWuhl->id,
                        'msgcontent'    => urlencode($msg) ,
                    ];
                    DingTalkController::sendCorpMessageText(json_encode($data));
                    sleep(1);
//                        DingTalkController::send($userWuhl->dtuserid, '',
//                            $msg,
//                            config('custom.dingtalk.agentidlist.erpmessage'));
                }
            }
        }
        $msg = '垫资总金额为' . number_format($padcapitalTotal, 4, '.', ',') . '元.';
        $this->info(number_format($padcapitalTotal, 4, '.', ','));
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
        else
        {
            $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
            if (isset($userWuhl))
            {
                $data = [
                    'userid'        => $userWuhl->id,
                    'msgcontent'    => urlencode($msg) ,
                ];
                DingTalkController::sendCorpMessageText(json_encode($data));
                sleep(1);
            }
        }
//        dd('aaa');

//        $padcapitalTotal = 0.0;
//        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
//        foreach ($soheads as $sohead)
//        {
//            $this->info($sohead->id);
//
//            $receiptpayment = $sohead->receiptpayments->sum('amount')  * 10000;
//            $payment = $sohead->payments->sum('amount');
//            $poheadAmountBy7550 = array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550;
//            $sohead_taxamount = isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0;
//            $sohead_poheadtaxamount = isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
//            $this->info('  ' . $receiptpayment);
//            $this->info('  ' . $payment);
//            $this->info('  ' . $poheadAmountBy7550);
//            $this->info('  ' . $sohead_taxamount);
//            $this->info('  ' . $sohead_poheadtaxamount);
//
//            if ($receiptpayment < ($payment + $poheadAmountBy7550))
//            {
//                $padcapital = $payment + $poheadAmountBy7550 - $receiptpayment;
//                $padcapitalTotal += $padcapital;
//                $msg = ($sohead->projectjc == "" ? $sohead->descrip : $sohead->projectjc)
//                    . '(' .$sohead->number . ')垫资'
//                    . number_format($padcapital, 4, '.', ',') . '元.';
//                $this->info('  ' . $msg);
//                if ($this->option('debug'))
//                {
//                    $touser = User::where('email', $this->argument('useremail'))->first();
//                    if (isset($touser))
//                    {
//                        $data = [
//                            'userid'        => $touser->id,
//                            'msgcontent'    => urlencode($msg) ,
//                        ];
//
//                        DingTalkController::sendCorpMessageText(json_encode($data));
//                    }
//                }
//                else
//                {
//                    $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
//                    if (isset($userWuhl))
//                    {
//                        $data = [
//                            'userid'        => $userWuhl->id,
//                            'msgcontent'    => urlencode($msg) ,
//                        ];
//                        DingTalkController::sendCorpMessageText(json_encode($data));
//                    }
//                }
//            }
//        }
//
//        $msg = '垫资总金额为' . number_format($padcapitalTotal, 4, '.', ',') . '元.';
//        $this->info(number_format($padcapitalTotal, 4, '.', ','));
//        if ($this->option('debug'))
//        {
//            $touser = User::where('email', $this->argument('useremail'))->first();
//            if (isset($touser))
//            {
//                $data = [
//                    'userid'        => $touser->id,
//                    'msgcontent'    => urlencode($msg) ,
//                ];
//
//                DingTalkController::sendCorpMessageText(json_encode($data));
//            }
//        }
//        else
//        {
//            $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
//            if (isset($userWuhl))
//            {
//                $data = [
//                    'userid'        => $userWuhl->id,
//                    'msgcontent'    => urlencode($msg) ,
//                ];
//                DingTalkController::sendCorpMessageText(json_encode($data));
//            }
//        }
    }
}
