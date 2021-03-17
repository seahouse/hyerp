<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class PurchaserequestReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:purchaserequest {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $query = Purchaseorder_hxold_simple::where('status', '>=', 0)
            ->where('status', '<', 10);
        $query->chunk(200, function ($poheads) {
            $i = 0;
            foreach ($poheads as $pohead)
            {
                // for test
                $i++;
                if ($i > 10) return;

                $add_date = Carbon::parse($pohead->add_date);
                $days = Carbon::today()->diffInDays($add_date);
                $this->info($pohead->id . '  ' . $days);
                Log::info($pohead->id . '  ' . $days);
                if ($days >= 10)
                {
                    // 72
                    $debugendDate = Carbon::parse($pohead->sohead->debugend_date);
                    $baseDate = Carbon::create(1900, 1, 1);
                    $msg_72 = '';
                    if ($debugendDate->gt($baseDate))
                        $msg_72 = '该项目已通过72小时，';

                    $msg = '采购申请单（' . $pohead->number. '）已创建' . $days . '天，对应项目：' . $pohead->descrip . '，设备：' . $pohead->productname . '，约定到货日期：' . Carbon::parse($pohead->agreed_arrival_date)->toDateString() . '，' . $msg_72 . '还未转为正式采购订单，请抓紧处理。';
                    if ($days >= 10 && $days < 20)
                        $this->sendMsg($msg, 225);      // WangXH
                    elseif ($days >= 20 && $days < 30)
                        $this->sendMsg($msg, 196);      // LiuHM
                    else
                        $this->sendMsg($msg, 186);      // LiuYJ
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
