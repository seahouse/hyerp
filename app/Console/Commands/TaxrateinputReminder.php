<?php

namespace App\Console\Commands;

use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use Illuminate\Console\Command;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use App\Http\Controllers\DingTalkController;

class TaxrateinputReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:taxrateinput {useremail=admin@admin.com} {--debug}';

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
//        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
//        $msgList = [];
//        foreach ($soheads as $sohead)
//        {
//            $this->info($sohead->id . '  ' . $sohead->amount);
//            $soheadtaxratetypeasses = $sohead->soheadtaxratetypeasses;
//            foreach ($soheadtaxratetypeasses as $soheadtaxratetypeass)
//            {
//                $this->info('  ' . $soheadtaxratetypeass->amount);
//            }
//            if ($sohead->amount > $soheadtaxratetypeasses->sum('amount'))
//            {
//                array_push($msgList, $sohead->number);
//            }
//        }
//        if (count($msgList) > 0)
//        {
//            $msg = "还未填写完整税率的订单如下: \n" . implode(", \n", $msgList);
//            $this->info('  ' . $msg);
//            if ($this->option('debug'))
//            {
//                $touser = User::where('email', $this->argument('useremail'))->first();
//                if (isset($touser))
//                {
//                    DingTalkController::send($touser->dtuserid, '',
//                        $msg,
//                        config('custom.dingtalk.agentidlist.erpmessage'));
//                }
//            }
//            else
//            {
//                // send msg to ZhouYP
//                $userZhouyp = Userold::where('email', 'zhouyanping@huaxing-east.com')->first();
//                if (isset($userZhouyp))
//                {
//                    DingTalkController::send($userZhouyp->dtuserid, '',
//                        $msg,
//                        config('custom.dingtalk.agentidlist.erpmessage'));
//                }
//
//                // send msg to Wuhl
//                $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
//                if (isset($userWuhl))
//                    DingTalkController::send($userWuhl->dtuserid, '',
//                        $msg,
//                        config('custom.dingtalk.agentidlist.erpmessage'));
//            }
//        }

        $poheads = Purchaseorder_hxold_simple::all();
        $msgList = [];
        foreach ($poheads as $pohead)
        {
            $this->info($pohead->id . '  ' . $pohead->amount);
            $poheadtaxrateasses = $pohead->poheadtaxrateasses;
            foreach ($poheadtaxrateasses as $poheadtaxrateass)
            {
                $this->info('  ' . $poheadtaxrateass->amount);
            }
            if ($pohead->amount > $poheadtaxrateasses->sum('amount'))
            {
                array_push($msgList, $pohead->number);
            }
        }
        if (count($msgList) > 0)
        {
            $msgList = array_slice($msgList, 0, 50);        // pre 50
            $msg = "还未填写完整税率的订单如下: \n" . implode(", \n", $msgList);
            $this->info('  ' . $msg);
            if ($this->option('debug'))
            {
                $touser = User::where('email', $this->argument('useremail'))->first();
                if (isset($touser))
                {
                    DingTalkController::send($touser->dtuserid, '',
                        $msg,
                        config('custom.dingtalk.agentidlist.erpmessage'));
                }
            }
            else
            {
                // send msg to Liuhm
                $userLiuhm = Userold::where('email', 'liuhuaming@huaxing-east.com')->first();
                if (isset($userLiuhm))
                {
                    DingTalkController::send($userLiuhm->dtuserid, '',
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
    }
}
