<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\DingTalkController;
use App\Models\System\Salarysheet;
use App\Models\System\Salarysheetreply;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Log;

class SalarysheetreplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function mstore(Request $request)
    {
        //
        $input = $request->all();
        $user = Auth::user();

        // Log::info($user->dtuserid);
        // Log::info(config('custom.dingtalk.agentidlist.approval'));
        // DingTalkController::send_link($user->dtuserid, '',
        //     url('mddauth/approval/approval-paymentrequestapprovals-mcreate'), '',
        //     '供应商付款审批', '来自XXX的付款申请单需要您审批.',
        //     config('custom.dingtalk.agentidlist.approval'));
        // return 'success';

        $salarysheet = Salarysheet::findOrFail($input['salarysheet_id']);

        if ($salarysheet->user_id != $user->id)
            return "您不是该工资单的接收人员。";

        $salarysheetreply = Salarysheetreply::create($input);


        // send dingtalk message.
        $touser_dtuserid = config('custom.dingtalk.salarysheet_reply_to', '');
        Log::info($touser_dtuserid);
        if (strlen($touser_dtuserid) > 0)
        {
            $msg = '';
            if ($input['status'] == 0)
                $msg = $salarysheet->user->name . '对' . $salarysheet->salary_date . '的工资单确认无误';
            elseif ($input['status'] == -1)
                $msg = $salarysheet->user->name . '对' . $salarysheet->salary_date . '的工资单存有异议';

            if (strlen($msg) > 0)
                $msg .= strlen($input['message'] > 0) ? '：'.$input['message'] : '。';

//            Log::info($msg);
            if (strlen($msg) > 0)
                DingTalkController::send($touser_dtuserid, '',
                    $msg,
                    config('custom.dingtalk.agentidlist.erpmessage'));


//            $data = [
//                [
//                    'key' => '申请人:',
//                    'value' => $paymentrequest->applicant->name,
//                ],
//                [
//                    'key' => '付款对象:',
//                    'value' => isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '',
//                ],
//                [
//                    'key' => '金额:',
//                    'value' => $paymentrequest->amount,
//                ],
//                [
//                    'key' => '付款类型:',
//                    'value' => $paymentrequest->paymenttype,
//                ],
//                [
//                    'key' => '对应项目:',
//                    'value' => isset($paymentrequest->purchaseorder_hxold->sohead->projectjc) ? $paymentrequest->purchaseorder_hxold->sohead->projectjc : '',
//                ],
//                [
//                    'key' => '商品:',
//                    'value' => isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '',
//                ],
//            ];
////                DingTalkController::send_oa(config('custom.dingtalk.approversettings.paymentrequest.cc_list'), '',
////                    url('mddauth/approval/approval-paymentrequests-' . $paymentrequest->id . ''), '',
////                    '', $paymentrequest->applicant->name . '提交的供应商付款审批已通过，抄送给你，请知晓。',
////                    $data, config('custom.dingtalk.agentidlist.approval'));
//
//            $msgcontent_data = [
//                'message_url' => url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'),
//                'pc_message_url' => '',
//                'head' => [
//                    'bgcolor' => 'FFBBBBBB',
//                    'text' => $paymentrequest->applicant->name . '的供应商付款审批'
//                ],
//                'body' => [
//                    'title' => $paymentrequest->applicant->name . '提交的供应商付款审批需要您审批。',
//                    'form' => $data
//                ]
//            ];
//            $msgcontent = json_encode($msgcontent_data);
//            $access_token = DingTalkController::getAccessToken();
//
//            $c = new DingTalkClient;
//            $req = new CorpMessageCorpconversationAsyncsendRequest;
//            $req->setMsgtype("oa");
//            $req->setAgentId(config('custom.dingtalk.agentidlist.approval'));
//            $req->setUseridList($touser->dtuserid);
//            $req->setToAllUser("false");
//            $req->setMsgcontent("$msgcontent");
//            $resp = $c->execute($req, $access_token);
//
////            DingTalkController::send_link($touser->dtuserid, '',
////                url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
////                '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.',
////                config('custom.dingtalk.agentidlist.approval'));
        }

        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
