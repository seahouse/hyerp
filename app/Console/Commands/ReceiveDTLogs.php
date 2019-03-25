<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpReportListRequest;
use App\Models\Dingtalk\Dtlog;
use App\Models\Dingtalk\Dtlogitem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

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
//            Log::info(json_encode($response));
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
//                            Log::info($itemArray);
                                    if (is_array($itemArray['value']))
                                        $itemArray['value'] = "";
                                    Dtlogitem::create($itemArray);
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
