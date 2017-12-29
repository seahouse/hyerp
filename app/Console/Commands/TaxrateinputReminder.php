<?php

namespace App\Console\Commands;

use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use Illuminate\Console\Command;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use App\Http\Controllers\DingTalkController;

class TaxrateinputReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:taxrateinput {useremail=admin@admin.com} {--sohead_id=} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '税率输入提醒: 向销售订单和采购订单的相关人员（ZYP、LHM）发送还未输入税率的订单信息';

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
//        $this->info($this->argument('useremail'));
//        $this->info($this->option('sohead_id'));
//        if ($this->option('sohead_id'))
//            $this->info('aaaa');
//        return;

        $query = Salesorder_hxold::where('status', '<>', -10);
        if ($this->option('sohead_id'))
            $query->where('id', $this->option('sohead_id'));
//        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
        $soheads = $query->select()->get();
        $msgList = [];
        foreach ($soheads as $sohead)
        {
            $this->info($sohead->id . '  ' . $sohead->amount);
            $soheadtaxratetypeasses = $sohead->soheadtaxratetypeasses;
            foreach ($soheadtaxratetypeasses as $soheadtaxratetypeass)
            {
                $this->info('  ' . $soheadtaxratetypeass->amount);
            }
            if ($sohead->amount > $soheadtaxratetypeasses->sum('amount'))
            {
                array_push($msgList, $sohead->number);
            }
        }
        if (count($msgList) > 0)
        {
            $msgList = array_slice($msgList, 0, 50);        // pre 50
            $msg = "还未填写完整税率的销售订单如下(前50条): \n" . implode(", \n", $msgList);
//            $msg = "还未填写完整税率的销售订单如下(前50条)3";
            $this->info('  ' . $msg);
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
//                    DingTalkController::send($touser->dtuserid, '',
//                        $msg,
//                        config('custom.dingtalk.agentidlist.erpmessage'));
                }
            }
            else
            {
                // send msg to ZhouYP
                $userZhouyp = User::where('email', 'zhouyanping@huaxing-east.com')->first();
                if (isset($userZhouyp))
                {
                    DingTalkController::send($userZhouyp->dtuserid, '',
                        $msg,
                        config('custom.dingtalk.agentidlist.erpmessage'));
                }

                // send msg to Wuhl
                $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
                if (isset($userWuhl))
                    DingTalkController::send($userWuhl->dtuserid, '',
                        $msg,
                        config('custom.dingtalk.agentidlist.erpmessage'));
            }
        }


//        $poheads = Purchaseorder_hxold_simple::all();
        $msgList = [];
        $transactorPoheads = [];
        $query = Purchaseorder_hxold_simple::orderBy('id');
        if ($this->option('sohead_id'))
            $query->where('sohead_id', $this->option('sohead_id'));
        $query->chunk(200, function ($poheads) use (&$msgList, &$transactorPoheads) {
            foreach ($poheads as $pohead)
            {
                $this->info($pohead->id . '  ' . $pohead->amount);
                $poheadtaxrateasses = $pohead->poheadtaxrateasses;
                foreach ($poheadtaxrateasses as $poheadtaxrateass)
                {
                    $this->info('  ' . $poheadtaxrateass->amount);
                }
                if ($pohead->amount <> $poheadtaxrateasses->sum('amount'))
                {
                    $contract_operator_id = $pohead->contract_operator_id;
                    if ($contract_operator_id == 0)
                        $contract_operator_id = 425;       // XiaMin
                    if (!array_key_exists($contract_operator_id, $transactorPoheads))
                    {
                        $transactorPoheads[$contract_operator_id] = [];
                    }
                    array_push($transactorPoheads[$contract_operator_id], $pohead->number);
                    array_push($msgList, $pohead->number);
                }

            }
        });

        foreach ($transactorPoheads as $key => $value)
        {
            $value = array_slice($value, 0, 50);        // pre 50
            $msg = "还未填写完整税率的采购订单如下(前50条): \n" . implode(", \n", $value);
            $this->info('  ' . $key);
            $this->info('  ' . $msg);

            $data = [
                'userid'        => $key,
                'msgcontent'    => urlencode($msg),
            ];

            if ($this->option('debug'))
            {
                $touser = User::where('email', $this->argument('useremail'))->first();
                if (isset($touser))
                {
                    $data['userid'] = $touser->id;
                    DingTalkController::sendCorpMessageText(json_encode($data));
//                    DingTalkController::send($touser->dtuserid, '',
//                        $msg,
//                        config('custom.dingtalk.agentidlist.erpmessage'));
                }
            }
            else
            {
                $transactor_hxold = Userold::where('user_hxold_id', $key)->first();
                if (isset($transactor_hxold))
                {
                    $touser = User::where('id', $transactor_hxold->user_id)->first();
                    if (isset($touser))
                    {
                        $data['userid'] = $touser->id;
                        DingTalkController::sendCorpMessageText(json_encode($data));

//                        DingTalkController::send($touser->dtuserid, '',
//                            $msg,
//                            config('custom.dingtalk.agentidlist.erpmessage'));
                    }
                }
            }
        }

        if ($this->option('sohead_id'))
        {
            if (count($msgList) > 0)
            {
                $msgList = array_slice($msgList, 0, 20);        // pre 50
                $msg = "填写有误的采购订单如下(前20条): \n" . implode(", \n", $msgList);
                $this->info('  ' . $msg);
                $data = [
                    'userid'        => 0,
                    'msgcontent'    => urlencode($msg),
                ];
                if ($this->option('debug'))
                {
                    $touser = User::where('email', $this->argument('useremail'))->first();
                    if (isset($touser))
                    {
                        $data['userid'] = $touser->id;
                        DingTalkController::sendCorpMessageText(json_encode($data));
                    }
                }
                else
                {
                    // send msg to Liuhm
                    $userLiuhm = User::where('email', 'liuhuaming@huaxing-east.com')->first();
                    if (isset($userLiuhm))
                    {
                        $data['userid'] = $userLiuhm->id;
                        DingTalkController::sendCorpMessageText(json_encode($data));
                    }

//                    // send msg to Wuhl
//                    $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
//                    if (isset($userWuhl))
//                        DingTalkController::send($userWuhl->dtuserid, '',
//                            $msg,
//                            config('custom.dingtalk.agentidlist.erpmessage'));
                }
            }
            else
            {
                $msg = "此订单对应的采购订单税率已全部填写正确:" . $this->option('sohead_id');
                $this->info('  ' . $msg);
                $data = [
                    'userid'        => 0,
                    'msgcontent'    => urlencode($msg),
                ];
                if ($this->option('debug'))
                {
                    $touser = User::where('email', $this->argument('useremail'))->first();
                    if (isset($touser))
                    {
                        $data['userid'] = $touser->id;
                        DingTalkController::sendCorpMessageText(json_encode($data));
                    }
                }
                else
                {
                    // send msg to Liuhm
                    $userLiuhm = User::where('email', 'liuhuaming@huaxing-east.com')->first();
                    if (isset($userLiuhm))
                    {
                        $data['userid'] = $userLiuhm->id;
                        DingTalkController::sendCorpMessageText(json_encode($data));
                    }
                }
            }
        }

    }
}
