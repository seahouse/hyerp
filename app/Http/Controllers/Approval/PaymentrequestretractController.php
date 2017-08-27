<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\HelperController;
use App\Models\Approval\Paymentrequestretract;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Paymentrequest;
use Auth;

class PaymentrequestretractController extends Controller
{
    private static $approvaltype_name = "供应商付款撤回";

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
        $input = $request->all();
        $input = HelperController::skipEmptyValue($input);


        $input['applicant_id'] = Auth::user()->id;


        // set approversetting_id
        $approvaltype_id = self::typeid();
        if ($approvaltype_id > 0)
        {
            $approversettingFirst = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->first();
            if ($approversettingFirst)
                $input['approversetting_id'] = $approversettingFirst->id;
            else
                $input['approversetting_id'] = -1;
        }
        else
            $input['approversetting_id'] = -1;
//        dd($input);
        if ($input['approversetting_id'] == -1)
            return "没有设置审批流，无法申请撤回";

        $paymentrequestretract = Paymentrequestretract::create($input);

        // 修改审批单状态为：撤回过程中（-3）
        if ($paymentrequestretract)
        {
            $paymentrequest =  Paymentrequest::findOrFail($paymentrequestretract->paymentrequest_id);
            if ($paymentrequest)
            {
                $paymentrequest->approversetting_id = -3;
                $paymentrequest->save();
            }
        }

        if ($paymentrequestretract)
        {
            // send dingtalk message.
            $touser = $paymentrequestretract->nextapprover();
            if ($touser)
            {
                // DingTalkController::send($touser->dtuserid, '',
                //     '来自' . $paymentrequest->applicant->name . '的付款单需要您审批.',
                //     config('custom.dingtalk.agentidlist.approval'));

                // DingTalkController::send_link($touser->dtuserid, '',
                //     url('approval/paymentrequestapprovals/' . $input['paymentrequest_id'] . '/mcreate'), '',
                //     '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.',
                //     config('custom.dingtalk.agentidlist.approval'));

                DingTalkController::send_link($touser->dtuserid, '',
                    url('mddauth/approval/approval-paymentrequestretractapprovals-' . $paymentrequestretract->id . '-mcreate'), '',
                    '供应商付款撤回审批', '来自' . $paymentrequestretract->applicant->name . '的付款申请单撤回需要您审批.',
                    config('custom.dingtalk.agentidlist.approval'));

                if (Auth::user()->email == "admin@admin.com")
                {
                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
                        url('mddauth/approval/approval-paymentrequestretractapprovals-' . $paymentrequestretract->id . '-mcreate'), '',
                        '供应商付款撤回审批', '来自' . $paymentrequestretract->applicant->name . '的付款申请单撤回需要您审批.', $paymentrequestretract,
                        config('custom.dingtalk.agentidlist.approval'));
                }

            }

        }

        return "success";
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

    public static function typeid()
    {
        $approvaltype = Approvaltype::where('name', self::$approvaltype_name)->first();
        if ($approvaltype)
        {
            return $approvaltype->id;
        }
        return 0;
    }
}
