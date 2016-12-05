<?php

namespace App\Http\Controllers\Approval;

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
        return view('approval/paymentrequestapprovals/mcreate', compact('paymentrequest', 'agent'));
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
        //     '供应商付款审批', '来自XXX的付款申请单需要您审批4.', 
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

            // DingTalkController::send_link($touser->dtuserid, '', 
            //     url('approval/paymentrequestapprovals/' . $input['paymentrequest_id'] . '/mcreate'), '',
            //     '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', 
            //     config('custom.dingtalk.agentidlist.approval'));

            DingTalkController::send_link($touser->dtuserid, '', 
                url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
                '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', 
                config('custom.dingtalk.agentidlist.approval'));

        }

        if ($paymentrequestapproval)
        {
            // send dingtalk message to applicant
            $str_result = $input['status'] == '0' ? '通过' : '未通过';
            DingTalkController::send($paymentrequest->applicant->dtuserid, '', 
                $user->name . ' 审批了您的(' . $paymentrequest->paymenttype . ' | ' . $paymentrequest->amount . ')付款单，审批结果：' . $str_result, 
                config('custom.dingtalk.agentidlist.approval')); 
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
