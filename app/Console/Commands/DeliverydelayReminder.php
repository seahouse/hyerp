<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class DeliverydelayReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:deliverydelay {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '交货超期提醒: 如果订单发生交货超期，向销售人员发送提醒.';

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
        $soheads = Salesorder_hxold::where('status', '<>', -10)->orderBy('plandeliverydate')->get();
        foreach ($soheads as $sohead)
        {
            $this->info($sohead->id);
            $this->info("\t" . $sohead->C);
            if ($sohead->C)
            {
                $startdate = Carbon::parse($sohead->startDate);
                $debugenddate = Carbon::parse($sohead->debugend_date);
                $passgasdate = Carbon::parse($sohead->passgasDate);
                $performanceacceptdate = Carbon::parse($sohead->performanceAcceptDate);
                $basedate = Carbon::create(1900, 1, 1, 0, 0, 0);
                if ($basedate->eq($startdate) && $basedate->eq($debugenddate) && $basedate->eq($passgasdate) && $basedate->eq($performanceacceptdate))
                {
                    $needsend = true;

                    // 如果是普通订单（非配件订单），则需要考虑是否已经有发货记录
                    if ($sohead->type == 0 && $sohead->senddetails->count() > 0)
                        $needsend = false;

                    if ($needsend)
                    {
                        $project = $sohead->project;
                        if (isset($project))
                        {
                            $poheads_project = $project->soheads;
                            foreach ($poheads_project as $pohead_project)
                            {
                                $startdate_project = Carbon::parse($pohead_project->startDate);
                                $debugenddate_project = Carbon::parse($pohead_project->debugend_date);
                                $passgasdate_project = Carbon::parse($pohead_project->passgasDate);
                                $performanceacceptdate_project = Carbon::parse($pohead_project->performanceAcceptDate);
                                if ($basedate->notEqualTo($startdate_project) || $basedate->notEqualTo($debugenddate_project) || $basedate->notEqualTo($passgasdate_project) || $basedate->notEqualTo($performanceacceptdate_project) || ($pohead_project->type == 0 && $pohead_project->senddetails->count() > 0))
                                {
                                    $needsend = false;
                                    break;
                                }
                            }
                        }
                    }


                    if ($needsend)
                    {
                        $plandeliverydate = Carbon::parse($sohead->plandeliverydate);
                        $today = Carbon::today();
                        $this->info("\t" . $today . "\t" . $sohead->plandeliverydate);
                        if ($today->diffInDays($plandeliverydate, false) == 14)
                        {
                            $msg = $sohead->projectjc . "（" . $sohead->number . "），按约定" . $plandeliverydate->toDateString() . "进场，目前已经延迟" . $plandeliverydate->diffInDays($today) . "天，如项目延迟进场则请及时与业主沟通延期后的准确时间，如业主无法给出准确时间则告知业主推迟半年，由此造成的涨价和仓储等损失由业主承担。将确定后的时间交财务部上传系统。";
                            $this->sendMsg($msg, $sohead->salesmanager_id);
                            $this->sendMsg($msg, 186);      // to LiuYJ
                            $this->sendMsg($msg, 8);        // to WuHL
                            $this->sendMsg($msg, 486);      // to ZhangJY
                        }
                        if ($today->diffInDays($plandeliverydate, false) == 7)
                        {
                            $msg = $sohead->projectjc . "（" . $sohead->number . "），按约定" . $plandeliverydate->toDateString() . "进场，目前已经延迟" . $plandeliverydate->diffInDays($today) . "天，如项目延迟进场则请及时与业主沟通延期后的准确时间，如业主无法给出准确时间则告知业主推迟半年，由此造成的涨价和仓储等损失由业主承担。将确定后的时间交财务部上传系统。";
                            $this->sendMsg($msg, $sohead->salesmanager_id);
                            $this->sendMsg($msg, 186);      // to LiuYJ
                            $this->sendMsg($msg, 8);        // to WuHL
                            $this->sendMsg($msg, 486);      // to ZhangJY
                        }
                        if ($today->diffInDays($plandeliverydate, false) < 0)
                        {
                            $this->info("\ttttttttt");
                            $msg = $sohead->projectjc . "（" . $sohead->number . "），按约定" . $plandeliverydate->toDateString() . "进场，目前已经延迟" . $plandeliverydate->diffInDays($today) . "天，如项目延迟进场则请及时与业主沟通延期后的准确时间，如业主无法给出准确时间则告知业主推迟半年，由此造成的涨价和仓储等损失由业主承担。将确定后的时间交财务部上传系统。";
                            $this->sendMsg($msg, $sohead->salesmanager_id);
                            $this->sendMsg($msg, 186);      // to LiuYJ
                            $this->sendMsg($msg, 8);        // to WuHL
                            $this->sendMsg($msg, 486);      // to ZhangJY
                        }
                    }
                }

            }
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
