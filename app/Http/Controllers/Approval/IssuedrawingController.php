<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\HelperController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Issuedrawing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use Auth;

class IssuedrawingController extends Controller
{
    private static $approvaltype_name = "下发图纸";

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

    public function mcreate()
    {
        //
        $config = DingTalkController::getconfig();
//        $agent = new Agent();
//
//        return view('approval/paymentrequests/mcreate', compact('config', 'agent'));
        return view('approval/issuedrawings/mcreate', compact('config'));
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
        $input = HelperController::skipEmptyValue($input);
        // dd($input);
        // dd($request->hasFile('paymentnodeattachments'));
        // dd($request->file('paymentnodeattachments'));
        // dd($request->file('paymentnodeattachments')->getClientOriginalExtension());
        // dd($request->input('amount', '0.0'));

        // $files = array_get($input,'paymentnodeattachments');
        // $destinationPath = 'uploads';
        // foreach ($files as $key => $file) {
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = $file->getClientOriginalName() . '.' . $extension;
        //     // dd($file->getClientOriginalName());
        //     $upload_success = $file->move($destinationPath, $fileName);
        // }




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

        $issuedrawing = Issuedrawing::create($input);
        dd($issuedrawing);





        // create paymentnodeattachments
        if ($paymentrequest)
        {
            $files = array_get($input,'paymentnodeattachments');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            foreach ($files as $key => $file) {
                if ($file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $paymentnodeattachment = new Paymentrequestattachment;
                    $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                    $paymentnodeattachment->type = "paymentnode";
                    $paymentnodeattachment->filename = $file->getClientOriginalName();
                    $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $paymentnodeattachment->save();
                }

            }

        }

        // create businesscontractattachments
        if ($paymentrequest)
        {
            $files = array_get($input,'businesscontractattachments');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            foreach ($files as $key => $file) {
                if ($file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $paymentnodeattachment = new Paymentrequestattachment;
                    $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                    $paymentnodeattachment->type = "businesscontract";
                    $paymentnodeattachment->filename = $file->getClientOriginalName();
                    $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $paymentnodeattachment->save();
                }

            }
        }

        // create images in the desktop
        if ($paymentrequest)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $paymentnodeattachment = new Paymentrequestattachment;
                        $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                        $paymentnodeattachment->type = "image";
                        $paymentnodeattachment->filename = $file->getClientOriginalName();
                        $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $paymentnodeattachment->save();
                    }

                }
            }

        }

        // create reimbursement images
        if ($paymentrequest)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            foreach ($images as $key => $value) {
                # code...
                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/paymentrequest/' . $paymentrequest->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                $parts = explode('/', $dir);
                $filename = array_pop($parts);
                $dir = '';
                foreach ($parts as $part) {
                    # code...
                    $dir .= "$part/";
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }
                }

                file_put_contents("$dir/$filename", file_get_contents($value));
                // file_put_contents('abcd.jpg', file_get_contents($value));

                // response()->download($value);
                // Storage::put('abcde.jpg', file_get_contents($value));
                // copy(storage_path('app') . '/' . $sFilename, '/images/' . $sFilename);

                // add image record
                $paymentrequestattachment = new Paymentrequestattachment;
                $paymentrequestattachment->paymentrequest_id = $paymentrequest->id;
                $paymentrequestattachment->type = "image";     // add a '/' in the head.
                $paymentrequestattachment->path = "/$dir$filename";     // add a '/' in the head.
                $paymentrequestattachment->save();
            }
        }

        if ($paymentrequest)
        {
            // send dingtalk message.
            $touser = $paymentrequest->nextapprover();
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
                    url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
                    '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.',
                    config('custom.dingtalk.agentidlist.approval'));

                if (Auth::user()->email == "admin@admin.com")
                {
                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
                        url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
                        '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', $paymentrequest,
                        config('custom.dingtalk.agentidlist.approval'));
                }

            }

        }

        return redirect('approval/mindexmy');
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
