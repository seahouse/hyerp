<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Models\Approval\Customerdeduction;
use App\Models\Approval\Customerdeductionattachment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Storage, Log;

class CustomerdeductionController extends Controller
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

    public function mcreate()
    {
        //
        $config = DingTalkController::getconfig();
        $client = new DingTalkClient();
        $req = new OapiProcessinstanceCspaceInfoRequest();
        $req->setUserId(Auth::user()->dtuserid);
        $response = $client->execute($req, $config['session']);
//        dd(json_decode(json_encode($response))->result->space_id);
        $config['spaceid'] = json_decode(json_encode($response))->result->space_id;
        return view('approval/customerdeductions/mcreate', compact('config'));
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
        $inputs = $request->all();
//        dd($inputs);

        $this->validate($request, [
            'customer_id'                 => 'required|integer|min:1',
            'sohead_id'                   => 'required|integer|min:1',
            'deductions_for'              => 'required',
            'amount'                       => 'required',
//            'issuedrawing_values'       => 'required',
//            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
//            'associated_approval_projectpurchase'            => 'required',
        ]);
//        $input = HelperController::skipEmptyValue($input);


        $inputs['applicant_id'] = Auth::user()->id;

        $customerdeduction = Customerdeduction::create($inputs);
//        dd($customerdeduction);

//        // create $additionsalesorderitems
//        if (isset($customerdeduction))
//        {
//            $additionsalesorder_items = json_decode($inputs['items_string']);
//            foreach ($additionsalesorder_items as $value) {
//                if (strlen($value->type) > 0)
//                {
//                    $item_array = json_decode(json_encode($value), true);
//                    $item_array['additionsalesorder_id'] = $customerdeduction->id;
//                    Additionsalesorderitem::create($item_array);
//                }
//            }
//        }

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($customerdeduction))
        {
            $files = array_get($inputs,'files');
            $destinationPath = 'uploads/approval/customerdeduction/' . $customerdeduction->id . '/files/';
            if (isset($files))
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();         // aa.xlsx
                        $extension = $file->getClientOriginalExtension();       // .xlsx
//                    Log::info('extension: ' . $extension);
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $customerdeductionattachment = new Customerdeductionattachment();
                        $customerdeductionattachment->customerdeduction_id = $customerdeduction->id;
                        $customerdeductionattachment->type = "file";
                        $customerdeductionattachment->filename = $originalName;
                        $customerdeductionattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $customerdeductionattachment->save();

                        array_push($fileattachments_url, url($destinationPath . $filename));
                        if (strcasecmp($extension, "pdf") == 0)
                            array_push($fileattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                        else
                        {
                            $filename2 = str_replace(".", "_", $filename);
                            array_push($fileattachments_url2, url("$destinationPath$filename2"));
                        }
                    }
                }
            }
        }

        $image_urls = [];
        // create images in the desktop
        if ($customerdeduction)
        {
            $files = array_get($inputs,'images');
            $destinationPath = 'uploads/approval/customerdeduction/' . $customerdeduction->id . '/images/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();       // .xlsx
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $customerdeductionattachment = new Customerdeductionattachment();
                        $customerdeductionattachment->customerdeduction_id = $customerdeduction->id;
                        $customerdeductionattachment->type = "image";
                        $customerdeductionattachment->filename = $originalName;
                        $customerdeductionattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $customerdeductionattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($customerdeduction)
        {
            $images = array_where($inputs, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/customerdeduction/' . $customerdeduction->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/customerdeduction/' . $customerdeduction->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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

//                $originalName = $file->getClientOriginalName();
                Storage::put($destinationPath . $filename, file_get_contents($value));

                file_put_contents("$dir/$filename", file_get_contents($value));


                // add image record
                $customerdeductionattachment = new Customerdeductionattachment();
                $customerdeductionattachment->customerdeduction_id = $customerdeduction->id;
                $customerdeductionattachment->type = "image";     // add a '/' in the head.
                $customerdeductionattachment->path = "/$dir$filename";     // add a '/' in the head.
                $customerdeductionattachment->save();

                array_push($image_urls, $value);
            }
        }
//        dd($customerdeduction);

        if (isset($customerdeduction))
        {
//            $inputs['totalamount'] = $customerdeduction->additionsalesorderitems->sum('amount');
            $inputs['image_urls'] = json_encode($image_urls);
//            $inputs['approvers'] = $customerdeduction->approvers();
            $response = ApprovalController::customerdeduction($inputs);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $customerdeduction->forceDelete();
//                Log::info(json_encode($inputs));
                dd('钉钉端创建失败: ' . $responsejson->errmsg);
            }
            else
            {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->process_instance_id;

                $response = DingTalkController::processinstance_get($process_instance_id);
                $responsejson = json_decode($response);
                $business_id = '';
                if ($responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->ding_open_errcode == 0)
                    $business_id = $responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->process_instance->business_id;

                $customerdeduction->process_instance_id = $process_instance_id;
                $customerdeduction->business_id = $business_id;
                $customerdeduction->save();

//                // send dingtalk message.
//                $touser = $mcitempurchase->nextapprover();
//                if ($touser)
//                {
//
////                    DingTalkController::send_link($touser->dtuserid, '',
////                        url('mddauth/approval/approval-paymentrequestapprovals-' . $mcitempurchase->id . '-mcreate'), '',
////                        '供应商付款审批', '来自' . $mcitempurchase->applicant->name . '的付款申请单需要您审批.',
////                        config('custom.dingtalk.agentidlist.approval'));
////
////                    if (Auth::user()->email == "admin@admin.com")
////                    {
////                        DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
////                            url('mddauth/approval/approval-paymentrequestapprovals-' . $mcitempurchase->id . '-mcreate'), '',
////                            '供应商付款审批', '来自' . $mcitempurchase->applicant->name . '的付款申请单需要您审批.', $mcitempurchase,
////                            config('custom.dingtalk.agentidlist.approval'));
////                    }
//
//                }
            }
        }


        dd('创建成功.');
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

    public static function updateStatusByProcessInstanceId($processInstanceId, $status)
    {
        $customerdeduction = Customerdeduction::where('process_instance_id', $processInstanceId)->firstOrFail();
        if (isset($customerdeduction))
        {
            $customerdeduction->status = $status;
            $customerdeduction->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $customerdeduction = Customerdeduction::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($customerdeduction)
        {
            $customerdeduction->forceDelete();
        }
    }
}
