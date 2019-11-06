<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Dingtalk\Dtlog;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Sitehoursreminderrecords;
use App\Models\System\User;
use App\Models\System\User_hxold;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SitehoursReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:sitehours {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '现场用工每超过500人工，1000人工，1500人工的项目,进行提醒';

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
        $soheads = Salesorder_hxold::where('status', '<>', -10)->get();
        foreach($soheads as $sohead)
        {
            $bReminder = false;
            $dtlogs=Dtlog::where('gctsrz_sohead_id',$sohead->id)->first();
            if(isset($dtlogs))
                continue;
            $totalhumandays_arr=$sohead->getDtlogHumandays_Xmjlsgrz($sohead->id);
            $totalhumandays = array_first($totalhumandays_arr)->days;
            $this->info($totalhumandays);
            if ($totalhumandays>=500 )
            {
                $reminderswitch = Sitehoursreminderrecords::where('sohead_id', $sohead->id)->first();
                if (! isset($reminderswitch))
                {
                    $bReminder = true;
                    $data = [
                        'sohead_id' => $sohead->id,
                        'humandays' => $totalhumandays,
                        'senddate' => Carbon::now(),
                    ];
                    Sitehoursreminderrecords::create($data);
                }
                else
                {
                    $humandays=$reminderswitch->humandays;
                    if($humandays/500==$totalhumandays/500)
                        $bReminder = false;
                    elseif($humandays/500<> $totalhumandays/500 && $totalhumandays/500 >$humandays/500)
                    {
                        $bReminder = true;
                        $data = [
                            'humandays' => $totalhumandays,
                            'senddate' => Carbon::now(),
                        ];
                        $reminderswitch->update($data);
                    }
                }
            }

            if ($bReminder)
            {
                $msg = $sohead->projectjc . "(" . $sohead->number . ")施工总人工数已经达到". $totalhumandays;
                $this->sendMsg($msg, 233);        // to wuyh
                $this->sendMsg($msg, 128);        // to songjh
                $this->sendMsg($msg,  $sohead->salesmanager_id);
                $this->sendMsg($msg,  8);        // to WuHL
                $this->sendMsg($msg,  16);        // to LiY
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
