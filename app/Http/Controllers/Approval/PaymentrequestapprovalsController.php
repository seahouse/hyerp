<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Models\Approval\Corporatepayment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Paymentrequestapproval;
use Auth, Log;
use Jenssegers\Agent\Agent;

class PaymentrequestapprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $paymentrequestapprovals = Paymentrequestapproval::latest('created_at')->paginate(15);
        return view('approval.paymentrequestapprovals.index', compact('paymentrequestapprovals'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mcreate($paymentrequestid)
    {
        //
        $paymentrequest = Paymentrequest::findOrFail($paymentrequestid);
        // $config = DingTalkController::getconfig();
        // return view('approval/reimbursementapprovals/mcreate', compact('reimbursement', 'config'));
        $touser = $paymentrequest->nextapprover();
        if (!$touser)
            return "此审批单无下一个审批人";

        if ($touser && $touser->id != Auth::user()->id)
            return "您无权限审批此审批单";

        $agent = new Agent();
        $config = DingTalkController::getconfig(config('custom.dingtalk.agentidlist.approval'));
        return view('approval/paymentrequestapprovals/mcreate', compact('paymentrequest', 'agent', 'config'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mstore(Request $request)
    {

        $input = $request->all();
        $user = Auth::user();

        // Log::info($user->dtuserid);
        // Log::info(config('custom.dingtalk.agentidlist.approval'));
        // DingTalkController::send_link($user->dtuserid, '', 
        //     url('mddauth/approval/approval-paymentrequestapprovals-mcreate'), '',
        //     '供应商付款审批', '来自XXX的付款申请单需要您审批.', 
        //     config('custom.dingtalk.agentidlist.approval'));
        // return 'success';

        $paymentrequest = Paymentrequest::findOrFail($input['paymentrequest_id']);
        $approversetting = Approversetting::findOrFail($paymentrequest->approversetting_id);

        if ($paymentrequest->nextapprover() && $paymentrequest->nextapprover()->id != $user->id)
            return "您不是该层级的审批人员。";

        $input['level'] = $approversetting->level;
        $input['approver_id'] = $user->id;

        $paymentrequestapproval = Paymentrequestapproval::create($input);

        if ($input['status'] == '0')
        {
            // 设置下一个审批人
            if ($paymentrequestapproval)
            {
                $approversettingNext = Approversetting::where('approvaltype_id', PaymentrequestsController::typeid())->where('level', '>', $approversetting->level)->orderBy('level')->first();
                if ($approversettingNext)
                    $paymentrequest->approversetting_id = $approversettingNext->id;
                else
                    $paymentrequest->approversetting_id = 0; // 已走完

                $paymentrequest->save();
            }
        }
        elseif ($input['status'] == '-1') {
            // 设置上一个审批人
            if ($paymentrequestapproval)
            {
                $paymentrequest = Paymentrequest::findOrFail($paymentrequestapproval->paymentrequest_id);
                $approversetting = Approversetting::findOrFail($paymentrequest->approversetting_id);
                $approversettingNext = Approversetting::where('approvaltype_id', PaymentrequestsController::typeid())->where('level', '<', $approversetting->level)->orderBy('level', 'desc')->first();
                if ($approversettingNext)
                    $paymentrequest->approversetting_id = $approversettingNext->id;
                else
                    $paymentrequest->approversetting_id = -2; // 已走完

                $paymentrequest->save();
            }
        }

        // send dingtalk message.
        $touser = $paymentrequest->nextapprover();
        if ($touser && strlen($touser->dtuserid) > 0)
        {
            // DingTalkController::send($touser->dtuserid, '', 
            //     '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', 
            //     config('custom.dingtalk.agentidlist.approval'));

            $data = [
                [
                    'key' => '申请人:',
                    'value' => $paymentrequest->applicant->name,
                ],
                [
                    'key' => '付款对象:',
                    'value' => isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '',
                ],
                [
                    'key' => '金额:',
                    'value' => $paymentrequest->amount,
                ],
                [
                    'key' => '付款类型:',
                    'value' => $paymentrequest->paymenttype,
                ],
                [
                    'key' => '对应项目:',
                    'value' => isset($paymentrequest->purchaseorder_hxold->sohead->projectjc) ? $paymentrequest->purchaseorder_hxold->sohead->projectjc : '',
                ],
                [
                    'key' => '商品:',
                    'value' => isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '',
                ],
            ];
//                DingTalkController::send_oa(config('custom.dingtalk.approversettings.paymentrequest.cc_list'), '',
//                    url('mddauth/approval/approval-paymentrequests-' . $paymentrequest->id . ''), '',
//                    '', $paymentrequest->applicant->name . '提交的供应商付款审批已通过，抄送给你，请知晓。',
//                    $data, config('custom.dingtalk.agentidlist.approval'));

            $msgcontent_data = [
                'message_url' => url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'),
                'pc_message_url' => '',
                'head' => [
                    'bgcolor' => 'FFBBBBBB',
                    'text' => $paymentrequest->applicant->name . '的供应商付款审批'
                ],
                'body' => [
                    'title' => $paymentrequest->applicant->name . '提交的供应商付款审批需要您审批。',
                    'form' => $data
                ]
            ];
            $msgcontent = json_encode($msgcontent_data);
            $access_token = DingTalkController::getAccessToken();

            $c = new DingTalkClient;
            $req = new CorpMessageCorpconversationAsyncsendRequest;
            $req->setMsgtype("oa");
            $req->setAgentId(config('custom.dingtalk.agentidlist.approval'));
            $req->setUseridList($touser->dtuserid);
//                $req->setDeptIdList("");
            $req->setToAllUser("false");
            $req->setMsgcontent("$msgcontent");
            $resp = $c->execute($req, $access_token);
        }

        if ($paymentrequestapproval)
        {
            // send dingtalk message to applicant
//            $str_result = $input['status'] == '0' ? '通过' : '未通过';
//            DingTalkController::send($paymentrequest->applicant->dtuserid, '',
//                $user->name . ' 审批了您的(' . $paymentrequest->paymenttype . ' | ' . $paymentrequest->amount . ')付款单，审批结果：' . $str_result,
//                config('custom.dingtalk.agentidlist.approval'));

            // when approval has passed, send message to WuHl
            if ($paymentrequest->approversetting_id <= 0)
            {
                $str_result = $paymentrequest->approversetting_id == 0 ? '已通过' : '未通过';

                $data = [
                    [
                        'key' => '申请人:',
                        'value' => $paymentrequest->applicant->name,
                    ],
                    [
                        'key' => '付款对象:',
                        'value' => isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '',
                    ],
                    [
                        'key' => '金额:',
                        'value' => $paymentrequest->amount,
                    ],
                    [
                        'key' => '付款类型:',
                        'value' => $paymentrequest->paymenttype,
                    ],
                    [
                        'key' => '对应项目:',
                        'value' => isset($paymentrequest->purchaseorder_hxold->sohead->projectjc) ? $paymentrequest->purchaseorder_hxold->sohead->projectjc : '',
                    ],
                    [
                        'key' => '商品:',
                        'value' => isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '',
                    ],
                ];

                $msgcontent_data = [
                    'message_url' => url('mddauth/approval/approval-paymentrequests-' . $paymentrequest->id . ''),
                    'pc_message_url' => '',
                    'head' => [
                        'bgcolor' => 'FFBBBBBB',
                        'text' => $paymentrequest->applicant->name . '的供应商付款审批'
                    ],
                    'body' => [
                        'title' => $paymentrequest->applicant->name . '提交的供应商付款审批' . $str_result .'，抄送给你，请知晓。',
                        'form' => $data
                    ]
                ];
                $msgcontent = json_encode($msgcontent_data);
                $access_token = DingTalkController::getAccessToken();

                $c = new DingTalkClient;
                $req = new CorpMessageCorpconversationAsyncsendRequest;
                $req->setMsgtype("oa");
                $req->setAgentId(config('custom.dingtalk.agentidlist.approval'));
                $req->setUseridList(config('custom.dingtalk.approversettings.paymentrequest.cc_list'));
//                $req->setDeptIdList("");
                $req->setToAllUser("false");
                $req->setMsgcontent("$msgcontent");
                $resp = $c->execute($req, $access_token);
                Log::info(json_encode($resp));

                $req->setUseridList($paymentrequest->applicant->dtuserid);
                $resp = $c->execute($req, $access_token);
                Log::info(json_encode($resp));
            }
            else
            {
                // 如果是对公付款生成的审批单，向对公付款发起人发送过程消息
                if (isset($paymentrequest->associated_approval_type) && $paymentrequest->associated_approval_type == 'corporatepayment')
                {
                    $corporatepayment = Corporatepayment::where('process_instance_id', $paymentrequest->associated_process_instance_id)->first();
                    if (isset($corporatepayment))
                    {
                        $applicant = $corporatepayment->applicant;
                        if (isset($applicant)) {
                            $msg = "你发起的对公账户付款审批单正在执行付款审批流程。对公付款审批单号：" . $corporatepayment->business_id . "，审批结果：" . ($paymentrequestapproval->status == 0 ? "通过" : "未通过") .
                                "，审批意见：" . $paymentrequestapproval->description . "，下一个审批人：" . $touser->name . "。";
                            if (isset($touser)) {
                                $data = [
                                    'userid' => $applicant->id,
                                    'msgcontent' => urlencode($msg),
                                ];

                                $response = DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                            }
                        }
                    }
                }
            }
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
