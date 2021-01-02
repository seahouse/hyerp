<?php

namespace App\Console\Commands;

use App\Http\Controllers\DingTalkController;
use App\Models\Sales\Receiptpayment_hxold;
use App\Models\System\User;
use App\Models\System\Userold;
use Illuminate\Console\Command;
use App\Models\System\Reminderswitch;
use Log;

class ReceiptrepeatReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:receiptrepeat {useremail=admin@admin.com} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '收款重复提醒：遍历收款信息，如果同一天从同一个客户收款同样金额的信息，给出提醒消息。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const TBL_NAME = 'vreceiptpayment';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $receiptpayments = Receiptpayment_hxold::select('sohead_id', 'date', 'amount')->groupBy('sohead_id', 'date', 'amount')->havingRaw('COUNT(*)>1')->get();
        $this->info("重复数据共有: " . count($receiptpayments));

        // 过滤到不需要提醒的数据
        $msgToSend = [];
        foreach ($receiptpayments as $receiptpayment) {
            $tbl = self::TBL_NAME;
            $id = $receiptpayment->sohead_id;
            $type = date('Ymd', strtotime($receiptpayment->date));

            $reminderswitch = Reminderswitch::where('tablename', $tbl)->where('tableid', $id)->where('type', $type)->where('value', '<>', 1)->first();
            $msg = "{$receiptpayment->sohead->projectjc}({$receiptpayment->sohead->number})在同一天有重复收款, 日期:$type, 金额:$receiptpayment->amount";
            if (isset($reminderswitch)) {
                $this->info("忽略 $msg");
                continue;
            }
            $this->info("添加 $msg");
            array_push($msgToSend, ['table' => $tbl, 'msg' => $msg, 'id' => $id, 'type' => $type]);
        }

        if (count($msgToSend) == 0) {
            $this->info('没有消息需要发送');
            return;
        }

        $this->sendMsg('收款重复提醒: ', 494);
        foreach ($msgToSend as $value) {
            $this->sendMsg($value, 494, 1);
        }
        $this->info('全部发送完成');
    }

    /**
     * $msgType 0:文本消息，1:卡片消息
     */
    public function sendMsg($msg, $userid_hxold = 0, $msgType = 0)
    {
        $usr = null;
        if ($this->option('debug')) {
            $usr = User::where('email', $this->argument('useremail'))->first();
        } elseif ($userid_hxold > 0) {
            $transactor_hxold = Userold::where('user_hxold_id', $userid_hxold)->first();
            if (isset($transactor_hxold)) {
                $usr = User::where('id', $transactor_hxold->user_id)->first();
            }
        }

        if (!isset($usr)) {
            $this->info('没有找到用户');
            return;
        }

        if ($msgType == 0) {
            $data = [
                'userid'        => $usr->id,
                'msgcontent'    => urlencode($msg),
            ];

            DingTalkController::sendCorpMessageTextReminder(json_encode($data));
        } else {
            $url = "http://www.huaxing-east.cn:2016/mddauth/approval/system-reminderswitches-storebyclick-{$msg['table']}-{$msg['id']}-{$msg['type']}-0";
            // Log::info($url);
            $data = [
                'msgtype'   => 'action_card',
                'action_card' => [
                    'title' => '收款重复提醒',
                    'markdown' => $msg['msg'],
                    'btn_orientation' => '0',
                    'btn_json_list' => [
                        [
                            'title' => '设置此消息不再提醒',
                            'action_url' => $url,
                        ],
                    ]
                ],
            ];

            $agentid = config('custom.dingtalk.agentidlist.erpreminder');
            $response = DingTalkController::sendActionCardMsg($usr->dtuserid, $agentid, $data);
            // $this->info($response);
        }
        sleep(1);
    }
}
