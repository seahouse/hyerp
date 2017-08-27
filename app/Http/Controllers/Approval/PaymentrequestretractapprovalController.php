<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestretract;
use App\Models\Approval\Paymentrequestretractapproval;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Log;
use Jenssegers\Agent\Agent;

class PaymentrequestretractapprovalController extends Controller
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

    public function mcreate($paymentrequestretractid)
    {
        //
        $paymentrequestretract = Paymentrequestretract::findOrFail($paymentrequestretractid);
        $paymentrequest = null;
        if ($paymentrequestretract)
            $paymentrequest = Paymentrequest::findOrFail($paymentrequestretract->paymentrequest_id);
        // return view('approval/reimbursementapprovals/mcreate', compact('reimbursement', 'config'));
        $touser = $paymentrequestretract->nextapprover();
        if (!$touser)
            return "此审批单无下一个审批人";
        if ($touser && $touser->id != Auth::user()->id)
            return "您无权限审批此审批单";

        $agent = new Agent();
        $config = DingTalkController::getconfig();
        return view('approval/paymentrequestretractapprovals/mcreate', compact('paymentrequestretract', 'agent', 'paymentrequest', 'config'));
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
        $input = $request->all();
        $user = Auth::user();

        $paymentrequestretract = Paymentrequestretract::findOrFail($input['paymentrequestretract_id']);
        $approversetting = Approversetting::findOrFail($paymentrequestretract->approversetting_id);

        if ($paymentrequestretract->nextapprover() && $paymentrequestretract->nextapprover()->id != $user->id)
            return "您不是该层级的审批人员。";

        $input['level'] = $approversetting->level;
        $input['approver_id'] = $user->id;

//        dd($input);
        $paymentrequestretractapproval = Paymentrequestretractapproval::create($input);

        if ($input['status'] == '0')
        {
            // 设置下一个审批人
            if ($paymentrequestretractapproval)
            {
                $approversettingNext = Approversetting::where('approvaltype_id', PaymentrequestretractController::typeid())->where('level', '>', $approversetting->level)->orderBy('level')->first();
                if ($approversettingNext)
                    $paymentrequestretract->approversetting_id = $approversettingNext->id;
                else
                {
                    $paymentrequestretract->approversetting_id = 0; // 已走完

                    // 更新对应的审批单的状态为已撤销（-4）
                    $paymentrequest = Paymentrequest::findOrFail($paymentrequestretract->paymentrequest_id);
                    if ($paymentrequest)
                    {
                        $paymentrequest->approversetting_id = -4;
                        $paymentrequest->save();
                    }
                }

                $paymentrequestretract->save();
            }
        }
        elseif ($input['status'] == '-1') {
            // 设置上一个审批人
            if ($paymentrequestretractapproval)
            {
                $paymentrequestretract = Paymentrequestretract::findOrFail($paymentrequestretractapproval->paymentrequestretract_id);
                $approversetting = Approversetting::findOrFail($paymentrequestretract->approversetting_id);
                $approversettingNext = Approversetting::where('approvaltype_id', PaymentrequestretractController::typeid())->where('level', '<', $approversetting->level)->orderBy('level', 'desc')->first();
                if ($approversettingNext)
                    $paymentrequestretract->approversetting_id = $approversettingNext->id;
                else
                {
                    $paymentrequestretract->approversetting_id = -2; // 已走完

                    // 更新对应的审批单的状态为已结束（0）
                    $paymentrequest = Paymentrequest::findOrFail($paymentrequestretract->paymentrequest_id);
                    if ($paymentrequest)
                    {
                        $paymentrequest->approversetting_id = 0;
                        $paymentrequest->save();
                    }
                }

                $paymentrequestretract->save();
            }
        }

        // send dingtalk message.
        Log::info('touser');
        $touser = $paymentrequestretract->nextapprover();
//        dd($touser);
        if ($touser && strlen($touser->dtuserid) > 0)
        {
            Log::info("供应商付款撤回审批 send_link");
            Log::info($touser->dtuserid);
            Log::info($paymentrequestretract->applicant->name);

            // Log::info($touser->dtuserid);
            DingTalkController::send_link($touser->dtuserid, '',
                url('mddauth/approval/approval-paymentrequestretractapproval-' . $paymentrequestretract->id . '-mcreate'), '',
                '供应商付款撤回审批', '来自' . $paymentrequestretract->applicant->name . '的付款申请单撤回需要您审批.',
                config('custom.dingtalk.agentidlist.approval'));
            // Log::info($paymentrequest->id);

        }

        if ($paymentrequestretractapproval)
        {
            Log::info("供应商付款撤回审批 send");
            Log::info($paymentrequestretract->applicant->dtuserid);
            // send dingtalk message to applicant
            $str_result = $input['status'] == '0' ? '通过' : '未通过';
            DingTalkController::send($paymentrequestretract->applicant->dtuserid, '',
                $user->name . ' 审批了您的付款申请单撤回申请，审批结果：' . $str_result,
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
