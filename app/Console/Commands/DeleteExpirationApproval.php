<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestapproval;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpirationApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:expirationapproval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除超期的审批单，并发送通知消息';

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
        $paymentrequests = Paymentrequest::where('approversetting_id', '>', 0)->get();
        foreach ($paymentrequests as $paymentrequest)
        {
            $this->info($paymentrequest->id . "\t" . $paymentrequest->created_at);
            if (Carbon::now()->gt($paymentrequest->created_at->addMonth(3)))
            {
                $this->info('greater.');
                $paymentrequestapprovals = Paymentrequestapproval::where('paymentrequest_id', $paymentrequest->id);
                $msg = "来自" . $paymentrequest->applicant->name . "的付款申请单（" . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : "") . ", "  . $paymentrequest->amount . "）因超3个月还未审批结束，被系统自动删除，请悉知。";
//                $msg = "来自" . $paymentrequest->applicant->name . "的付款申请单（" . isset($paymentrequest->supplier_hxold->name) ? isset($paymentrequest->supplier_hxold->name) : "" . ", "  . $paymentrequest->amount . "）因超3个月还未审批结束，被系统自动删除，请悉知。.";
                // send msg to approver
                foreach ($paymentrequestapprovals as $paymentrequestapproval)
                {
                    $this->info("\t" . $paymentrequestapproval->approver_id);
                    $data = [
                        'userid'        => $paymentrequestapproval->approver_id,
                        'msgcontent'    => urlencode($msg) ,
                    ];
                    DingTalkController::sendCorpMessageText(json_encode($data));
                    sleep(1);
                }
                // send msg to approver
                $this->info("\t" . $paymentrequest->applicant_id . "\t" . $msg);
                $data = [
                    'userid'        => $paymentrequest->applicant_id,
                    'msgcontent'    => urlencode($msg) ,
                ];
                DingTalkController::sendCorpMessageText(json_encode($data));
                sleep(1);
                // send msg to WuHL
                $whl = User::where('email', "wuhaolun@huaxing-east.com")->first();
                if (isset($whl))
                {
                    $data = [
                        'userid'        => $whl->id,
                        'msgcontent'    => urlencode($msg) ,
                    ];
                    DingTalkController::sendCorpMessageText(json_encode($data));
                    sleep(1);
                }

                $paymentrequest->approversetting_id = -5;
                $paymentrequest->save();
            }
        }
    }
}
