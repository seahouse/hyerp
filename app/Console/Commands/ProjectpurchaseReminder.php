<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class ProjectpurchaseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:projectpurchase {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '工程采购提醒：当工程采购审批单通过后，如果超过2个月还没有完善对应的采购订单，向发起人发送提醒。';

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
        $query = Purchaseorder_hxold_simple::where('amount', '>', 0.0)
            ->where('status', 10)
            ->where('type', '工程')
            ->where('business_id', '<>', '')
            ->where('vendinfo_id', '<=', 0)->orderBy('amount', 'desc');
        $query->chunk(200, function ($poheads) {
            foreach ($poheads as $pohead)
            {
                $this->info($pohead->id . '  ' . $pohead->amount);
                $pohead_signdate = Carbon::parse($pohead->pohead_signdate);
                if (Carbon::today()->gt($pohead_signdate->addMonth(2)))
                {
                    $msg = '采购订单（' . $pohead->number. '）还未找采购部补全供应商等信息，请抓紧处理。对应的工程审批单号：' . $pohead->business_id;
//                    Log::info($msg);
                    $this->sendMsg($msg, $pohead->applicant_id);
                }
            }
        });
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

                DingTalkController::sendCorpMessageTextReminder(json_encode($data));
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
