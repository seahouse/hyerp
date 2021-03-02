<?php

namespace App\Console\Commands;

use App\Models\Purchase\Payment_hxold;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\DingTalkController;
use App\Models\System\User;
use App\Models\System\Userold;
use Log;

class SupplierticketReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:supplierticket {useremail=admin@admin.com} {--totalto=} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '供应商开票提醒: 采购订单，如果已经付款60%以上且到货但未开票， 提醒采购经办人催促供应商开票';

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
        $msg = '以下消息为超额付款的采购订单：';
        $this->sendMsg($msg, 8);        // to WuHL
        $this->sendMsg($msg, 196);      // to LiuHM

        $msgWuhl = [];
        $query = Purchaseorder_hxold_simple::where('amount', '>', 0.0)->orderBy('amount', 'desc');
        $query->whereRaw('amount_paid > amount - amount_vendordeduction + deduction_received')->whereRaw('amount_ticketed < amount_paid');
        $query->chunk(200, function ($poheads) use (&$msgWuhl) {
            foreach ($poheads as $pohead) {
                $this->info($pohead->id . '  ' . $pohead->amount);
                $msg = '采购订单（' . $pohead->number . '）的合同金额为' . $pohead->amount . '，付款金额为' . $pohead->amount_paid . '，';
                if ($pohead->amount_vendordeduction > 0.0)
                    $msg .= '扣款金额为' . $pohead->amount_vendordeduction . '，';
                $msg .= '付款金额大于应付金额，请检查。供应商：' . $pohead->supplier_name;
                //                Log::info($msg);

                $this->sendMsg($msg, $pohead->transactor_id);
                //                $this->sendMsg($msg, 379);       // to ZhouYP

                $supplier_id = $pohead->vendinfo_id;
                if (!array_key_exists($supplier_id, $msgWuhl)) {
                    $msgWuhl[$supplier_id]["name"] = $pohead->supplier_name;
                    $msgWuhl[$supplier_id]["messages"] = [];
                }
                array_push($msgWuhl[$supplier_id]["messages"], $pohead->amount_paid - $pohead->amount + $pohead->amount_vendordeduction);
            }
        });

        //        Log:;info(json_encode($msgWuhl));
        $msgWuhl = array_sort($msgWuhl, function ($value) {
            return 0 - array_sum($value["messages"]);
        });
        foreach ($msgWuhl as $key => $value) {
            //            $value = array_slice($value, 0, 50);        // pre 50
            if (array_sum($value["messages"]) > 50000.0) {
                $msg = $value["name"] . "累计" . count($value["messages"]) . "个采购订单，合计超付" . array_sum($value["messages"]) . "元。";
                //                Log::info($msg);
            }

            $data = [
                'userid'        => $key,
                'msgcontent'    => urlencode($msg),
            ];
            if ($this->option('totalto')) {
                $touser = User::where('email', $this->option('totalto'))->first();
                if (isset($touser)) {
                    $data['userid'] = $touser->id;
                    DingTalkController::sendCorpMessageText(json_encode($data));
                    sleep(1);
                }
            }
        }


        $msg = '以下消息为需要向供应商催开票据的订单：';
        //        $this->sendMsg($msg, 379);      // to ZhouYP
        $this->sendMsg($msg, 8);        // to WuHL
        $this->sendMsg($msg, 196);      // to LiuHM
        $this->sendMsg($msg, 186);      // to LiuYJ

        $msgWuhl = [];
        $query = Purchaseorder_hxold_simple::where('amount', '>', 0.0)->orderBy('amount', 'desc');
        $query->where(function ($query) {
            $query->where('arrival_status', '全部到货')
                ->orWhere('arrival_status', '未到货');
        });
        // 付款超过70%后，发票按照100%算；付款低于70%的，发票按照已付款金额为标准
        //        $query->whereRaw('amount_paid / amount > 0.6')->whereRaw('amount_paid / amount <= 1.0')->whereRaw('amount_ticketed < amount_paid');
        $query->where(function ($query) {
            $query->whereRaw('(amount_paid + amount_ticketed_purchase_noaudit) / amount >= 0.7 and (amount_ticketed + amount_ticketed_purchase_noaudit) < amount')
                ->orWhereRaw('(amount_paid + amount_ticketed_purchase_noaudit) / amount < 0.7 and (amount_ticketed + amount_ticketed_purchase_noaudit) < amount_paid');
        });

        $msg = '';
        $query->chunk(200, function ($poheads) use (&$msgWuhl) {
            foreach ($poheads as $pohead) {
                $this->info($pohead->id . '  ' . $pohead->amount);
                $msg = '采购订单（' . $pohead->number . '）已付款' . $pohead->amount_paid . '（' . number_format($pohead->amount_paid / $pohead->amount * 100, 2) . '%），' . '开票金额' . ($pohead->amount_ticketed + $pohead->amount_ticketed_purchase_noaudit) . '，请抓紧向' . $pohead->supplier_name .  '催开剩余票据。';
                //                Log::info($msg);

                $needReminder = true;
                if ($pohead->arrival_status == '未到货') {
                    $payment = Payment_hxold::where('pohead_id', $pohead->id)->orderBy('payment_date', 'desc')->first();
                    if (isset($payment)) {
                        $payment_date = Carbon::parse($payment->payment_date);

                        /// 修改：去掉20天的延迟提醒，修改为立马提醒，2019/20/22
                        //                        if (Carbon::now()->gt($payment_date->addDays(20)))
                        {
                            $needReminder = true;

                            $supplier_id = $pohead->vendinfo_id;
                            if (!array_key_exists($supplier_id, $msgWuhl)) {
                                $msgWuhl[$supplier_id]["name"] = $pohead->supplier_name;
                                $msgWuhl[$supplier_id]["unticketedamountlist"] = [];
                                $msgWuhl[$supplier_id]["paidamountlist"] = [];
                                $msgWuhl[$supplier_id]["ticketedamountlist"] = [];
                            }
                            // 付款超过70%后，发票按照100%算；付款低于70%的，发票按照已付款金额为标准
                            array_push($msgWuhl[$supplier_id]["unticketedamountlist"], $pohead->amount_paid / $pohead->amount >= 0.7 ? $pohead->amount - $pohead->amount_ticketed - $pohead->amount_ticketed_purchase_noaudit : $pohead->amount_paid - $pohead->amount_ticketed - $pohead->amount_ticketed_purchase_noaudit);
                            array_push($msgWuhl[$supplier_id]["paidamountlist"], $pohead->amount_paid);
                            array_push($msgWuhl[$supplier_id]["ticketedamountlist"], $pohead->amount_ticketed + $pohead->amount_ticketed_purchase_noaudit);
                        }
                        //                        else
                        //                            $needReminder = false;
                    }
                }

                if ($needReminder) {
                    $this->sendMsg($msg, $pohead->transactor_id);
                    //                    $this->sendMsg($msg, 186);      // to LiuYJ
                }
            }
        });

        // sort by total unticketedamountlist amount.
        //        Log::info(json_encode($msgWuhl));
        $msgWuhl = array_sort($msgWuhl, function ($value) {
            return 0 - array_sum($value["unticketedamountlist"]);
        });
        //        Log::info(json_encode($msgWuhl));
        foreach ($msgWuhl as $key => $value) {
            //            $value = array_slice($value, 0, 50);        // pre 50
            // moidfy from 100000.0 to 0.0, 2018/12/18,
            if (array_sum($value["unticketedamountlist"]) > 0.0) {
                $msg = $value["name"] . "累计" . count($value["unticketedamountlist"]) . "个采购订单，合计欠票" . array_sum($value["unticketedamountlist"]) . "元。";
                //                Log::info($msg);
                $this->sendMsg($msg, 186);      // to LiuYJ

                if (array_sum($value["unticketedamountlist"]) > 300000.0)
                    $this->sendMsg($msg, 8);      // to WuHL
            }

            //            $data = [
            //                'userid'        => $key,
            //                'msgcontent'    => urlencode($msg),
            //            ];
            //            if ($this->option('totalto'))
            //            {
            //                $touser = User::where('email', $this->option('totalto'))->first();
            //                if (isset($touser))
            //                {
            //                    $data['userid'] = $touser->id;
            //                    DingTalkController::sendCorpMessageText(json_encode($data));
            //                    sleep(1);
            //                }
            //            }
        }
    }

    public function sendMsg($msg, $userid_hxold = 0)
    {
        if ($this->option('debug')) {
            $touser = User::where('email', $this->argument('useremail'))->first();
            if (isset($touser)) {
                $data = [
                    'userid'        => $touser->id,
                    'msgcontent'    => urlencode($msg),
                ];

                DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                sleep(1);
            }
        } elseif ($userid_hxold > 0) {
            $transactor_hxold = Userold::where('user_hxold_id', $userid_hxold)->first();
            if (isset($transactor_hxold)) {
                $transactor = User::where('id', $transactor_hxold->user_id)->first();
                if (isset($transactor)) {
                    $data = [
                        'userid'        => $transactor->id,
                        'msgcontent'    => urlencode($msg),
                    ];
                    //                    Log::info($transactor->name);
                    DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                    sleep(1);
                }
            }
        }
    }
}
