<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpReportListRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiReportCommentListRequest;
use App\Models\Dingtalk\Dtlog;
use App\Models\Dingtalk\Dtlogcomment;
use App\Models\Dingtalk\Dtlogitem;
use App\Models\Sales\Salesorder_hxold;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log, DB;

class ReceiveDTLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dt:receivelogs {days=2} {--template=日报}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '接收钉钉日志数据：获取钉钉日志数据，将数据保存到本地。';

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
        $client = new DingTalkClient();
        $request = new CorpReportListRequest();
        $session = DingTalkController::getAccessToken();

        $statDate = Carbon::today()->subDays($this->argument('days'));
        $endDate = Carbon::now();
        if ($statDate->diffInDays($endDate) >= 179)
            $endDate = $statDate->copy()->addDays(179);             // do not exceed 180 days.

        $this->info($statDate->toDateTimeString());
        $this->info($endDate->toDateTimeString());
        $startTime = $statDate->timestamp * 1000;
        $endTime = $endDate->timestamp * 1000;
        $this->info($startTime);
        $this->info($endTime);
        $request->setStartTime("$startTime");
        $request->setEndTime("$endTime");
        $this->info($this->option('template'));
//        Log::info($this->option('template'));
        $request->setTemplateName($this->option('template'));
        $request->setSize("10");

        $cursor = 0;
        while (true)
        {
            $request->setCursor("$cursor");
            $response = $client->execute($request, $session);
            Log::info(json_encode($response));
            $this->info(json_encode($response));

            if ($response->result->ding_open_errcode == "0")
            {
                if (isset($response->result->result->data_list))
                {
                    foreach ($response->result->result->data_list->report_oapi_vo as $report)
                    {
                        foreach ($report->contents->json_object as $item)
                        {
//                    Log:info($item->key . ":" . $item->value);
//                    Log::info(json_encode($item));
//                    $itemArray = json_decode(json_encode($item), true);
//                    Log::info($itemArray);
//                    dd($itemArray);
                        }
//                Log::info($report->creator_name);
//                Log::info($report->dept_name);
//                Log::info($report->remark);
//                Log::info($report->template_name);

                        $xmjlsgrz_sohead_id = 0;
                        $xmjlsgrz_log_date = '';
                        $xmjlsgrz_logitem_6 = 0;
                        $xmjlsgrz_logitem_6_1 = 0;
                        $xmjlsgrz_logitem_6_2 = 0;
                        $xmjlsgrz_logitem_6_3 = 0;
                        $xmjlsgrz_logitem_6_4 = 0;
                        $xmjlsgrz_logitem_6_5 = 0;
                        $dtlog = Dtlog::where('report_id', $report->report_id)->first();
                        if (!isset($dtlog))
                        {
                            $input = json_decode(json_encode($report), true);
                            $input['create_time'] = Carbon::createFromTimestamp((double)$report->create_time / 1000);
//                    Log::info((double)$report->create_time / 1000);
//                    Log::info(Carbon::createFromTimestamp((double)$report->create_time / 1000));
                            if (is_array($input['remark']))
                                $input['remark'] = "";
                            $dtlog = Dtlog::create($input);

                            if (isset($dtlog))
                            {
                                foreach ($report->contents->json_object as $item)
                                {
                                    $itemArray = json_decode(json_encode($item), true);
                                    $itemArray['dtlog_id'] = $dtlog->id;
                                    if (is_array($itemArray['value']))
                                        $itemArray['value'] = "";
                                    Dtlogitem::create($itemArray);

                                    if ($this->option('template') == '项目经理施工日志')
                                    {
                                        if ($itemArray['key'] == '2、工程项目名称')
                                        {
                                            $soheads = Salesorder_hxold::all();
                                            foreach ($soheads as $sohead)
                                            {
                                                if (strpos($itemArray['value'], $sohead->number) !== false)
                                                {
                                                    $dtlog->update(['xmjlsgrz_sohead_id' => $sohead->id]);
                                                    $xmjlsgrz_sohead_id = $sohead->id;
                                                    break;
                                                }
                                            }
                                        }
                                        if ($itemArray['key'] == '1、日志日期')
                                        {
                                            $xmjlsgrz_log_date = Carbon::parse($itemArray['value']);
                                        }
                                        if ($itemArray['key'] == '6、今日安装队总人数')
                                            $xmjlsgrz_logitem_6 = $itemArray['value'];
                                        if ($itemArray['key'] == '6-1、其中机组人员')
                                            $xmjlsgrz_logitem_6_1 = $itemArray['value'];
                                        if ($itemArray['key'] == '6-2、其中电气人员')
                                            $xmjlsgrz_logitem_6_2 = $itemArray['value'];
                                        if ($itemArray['key'] == '6-3、其中保温人员')
                                            $xmjlsgrz_logitem_6_3 = $itemArray['value'];
                                        if ($itemArray['key'] == '6-4、其中管道人员')
                                            $xmjlsgrz_logitem_6_4 = $itemArray['value'];
                                        if ($itemArray['key'] == '6-5、安装队管理人员')
                                            $xmjlsgrz_logitem_6_5 = $itemArray['value'];
                                    }
                                    if ($this->option('template') == '工程调试日志' && $itemArray['key'] == '工程项目名称')
                                    {
                                        $soheads = Salesorder_hxold::all();
                                        foreach ($soheads as $sohead)
                                        {
                                            if (strpos($itemArray['value'], $sohead->number) !== false)
                                            {
                                                $dtlog->update(['gctsrz_sohead_id' => $sohead->id]);
                                                break;
                                            }
                                        }
                                    }
                                }

                                // 下载日志评论, 默认最多100个评论，暂不考虑超过100的多次请求
                                $client_comment = new DingTalkClient();
                                $request_comment = new OapiReportCommentListRequest();
                                $request_comment->setReportId("$report->report_id");
                                $response_comment = $client_comment->execute($request_comment, $session);
                                Log::info(json_encode($response_comment));
                                if ($response_comment->errcode == "0")
                                {
                                    if (isset($response_comment->result->comments))
                                    {
                                        foreach ($response_comment->result->comments->report_comment_vo as $comment)
                                        {
                                            $inputs_comment = json_decode(json_encode($comment), true);
                                            $inputs_comment['dtlog_id'] = $dtlog->id;
                                            Log::info($inputs_comment);
                                            Dtlogcomment::create($inputs_comment);
                                        }
                                    }
                                }
                            }
                        }

                        // 项目经理施工日志，补全之前的日志，如果超过一定有效天数，则不增加
                        if ($xmjlsgrz_sohead_id > 0 && null != $xmjlsgrz_log_date)
                        {
                            $dtlog_last = DB::table('dtlogs')
                                ->leftJoin('dtlogitems', 'dtlogitems.dtlog_id', '=', 'dtlogs.id')
                                ->where('xmjlsgrz_sohead_id', $xmjlsgrz_sohead_id)
                                ->where('generation_reason', 0)
                                ->where('template_name', '项目经理施工日志')
                                ->where('dtlogitems.key', '1、日志日期')
                                ->where('dtlogitems.value', '<', $xmjlsgrz_log_date->toDateString())
                                ->orderBy('dtlogitems.value', 'desc')
                                ->select('dtlogs.*', 'dtlogitems.value')
                                ->first();
                            if (isset($dtlog_last))
                            {
                                $xmjlsgrz_log_lastdate = Carbon::parse($dtlog_last->value);
                                while (true)
                                {
                                    $xmjlsgrz_log_lastdate->addDay();
                                    if ($xmjlsgrz_log_lastdate->gte($xmjlsgrz_log_date))
                                        break;

                                    $dtlog_temp = DB::table('dtlogs')
                                        ->leftJoin('dtlogitems', 'dtlogitems.dtlog_id', '=', 'dtlogs.id')
                                        ->where('xmjlsgrz_sohead_id', $xmjlsgrz_sohead_id)
                                        ->where('template_name', '项目经理施工日志')
                                        ->where('dtlogitems.key', '1、日志日期')
                                        ->where('dtlogitems.value', $xmjlsgrz_log_lastdate->toDateString())
                                        ->select('dtlogs.*', 'dtlogitems.value')
                                        ->first();
                                    if (!isset($dtlog_temp))
                                    {
                                        $dtlog = Dtlog::create([
                                            'report_id'             => $xmjlsgrz_sohead_id . '_' . $xmjlsgrz_log_lastdate->toDateString(),
                                            'create_time'           => Carbon::now()->toDateTimeString(),
                                            'creator_id'            => '0',
                                            'creator_name'          => '系统',
                                            'remark'                 => '日志补全',
                                            'template_name'         => '项目经理施工日志',
                                            'generation_reason'    => 1,
                                            'xmjlsgrz_sohead_id'   => $xmjlsgrz_sohead_id,
                                        ]);
                                        if (isset($dtlog))
                                        {
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '1、日志日期',
                                                'value'              => $xmjlsgrz_log_lastdate->toDateString(),
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6、今日安装队总人数',
                                                'value'              => $xmjlsgrz_logitem_6,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6-1、其中机组人员',
                                                'value'              => $xmjlsgrz_logitem_6_1,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6-2、其中电气人员',
                                                'value'              => $xmjlsgrz_logitem_6_2,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6-3、其中保温人员',
                                                'value'              => $xmjlsgrz_logitem_6_3,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6-4、其中管道人员',
                                                'value'              => $xmjlsgrz_logitem_6_4,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                            Dtlogitem::create([
                                                'dtlog_id'          => $dtlog->id,
                                                'key'                => '6-5、安装队管理人员',
                                                'value'              => $xmjlsgrz_logitem_6_5,
                                                'sort'               => 0,
                                                'type'               => 0,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }
            else
            {
                Log::info("获取钉钉日志失败: " . $response->result->error_msg);
                break;
            }

            if (isset($response->result->result->has_more))
            {
                if ($response->result->result->has_more == "false")
                    break;
                else
                {
                    $cursor = $response->result->result->next_cursor;
//                Log::info("cursor:" . $cursor);
                }
            }
            else
                break;

        }
//        $response = $client->execute($request, $session);



    }
}
