<?php

namespace App\Console\Commands;

use App\Models\Purchase\Purchaseorder_hxold_simple;
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
    protected $signature = 'reminder:supplierticket {useremail=admin@admin.com} {--debug}';

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
        $query = Purchaseorder_hxold_simple::where('amount_paid', '>', 0.0)->orderBy('id');
        $query->where('arrival_status', '全部到货');
        $query->whereRaw('amount_paid / amount > 0.6')->whereRaw('amount_ticketed < amount_paid');
        $msg = '';
        $query->chunk(200, function ($poheads) {
            foreach ($poheads as $pohead)
            {
                $this->info($pohead->id . '  ' . $pohead->amount);
                Log::info($pohead->id . '  ' . $pohead->amount);
                $msg = '采购订单（' . $pohead->number . '）已付款' . $pohead->amount_paid . '（' . number_format($pohead->amount_paid / $pohead->amount * 100, 2) . '%）且已全部到货，' . '开票金额' . $pohead->amount_ticketed . '，请抓紧向' . $pohead->supplier_name .  '催收剩余票据。';
                Log::info($msg);

                if ($this->option('debug'))
                {
                    $transactor_hxold = Userold::where('user_hxold_id', $pohead->transactor_id)->first();
                    if (isset($transactor_hxold))
                    {
                        $transactor = User::where('id', $transactor_hxold->user_id)->first();
                        if (isset($transactor))
                        {
                            $data = [
                                'userid'        => $transactor->id,
                                'msgcontent'    => urlencode($msg) ,
                            ];
                            Log::info($transactor->name);
                        }
                    }

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
                    $transactor_hxold = Userold::where('user_hxold_id', $pohead->transactor_id)->first();
                    if (isset($transactor_hxold))
                    {
                        $transactor = User::where('id', $transactor_hxold->user_id)->first();
                        if (isset($transactor))
                        {
                            $data = [
                                'userid'        => $transactor->id,
                                'msgcontent'    => urlencode($msg) ,
                            ];
                            Log::info($transactor->name);
                            DingTalkController::sendCorpMessageText(json_encode($data));
                            sleep(1);
                        }
                    }

//                    $userWuhl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
//                    if (isset($userWuhl))
//                    {
//                        $data = [
//                            'userid'        => $userWuhl->id,
//                            'msgcontent'    => urlencode($msg) ,
//                        ];
//                        DingTalkController::sendCorpMessageText(json_encode($data));
//                        sleep(1);
//                    }
                }
            }
        });
    }
}
