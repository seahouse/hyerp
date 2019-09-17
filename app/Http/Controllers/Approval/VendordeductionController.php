<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Models\Approval\Vendordeduction;
use App\Models\Approval\Vendordeductionattachment;
use App\Models\Approval\Vendordeductionitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Storage;

class VendordeductionController extends Controller
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
        return view('approval/vendordeductions/mcreate', compact('config'));
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
//        $vendordeduction = new Vendordeduction;
//        $vendordeduction->techdepart = '工艺二室';
//        $vendordeduction->outsourcingtype = '宣城生产中心生产队伍';
//        $vendordeduction->problemlocation = '项目现场';
//        $techdepart = $vendordeduction->approvers();
//        dd($techdepart);

        $input = $request->all();

        $this->validate($request, [
            'pohead_id'                   => 'required|integer|min:1',
//            'purchasereason'             => 'required',
//            'issuedrawing_values'       => 'required',
            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
        ]);
//        $input = HelperController::skipEmptyValue($input);


//        if ($input['totalprice'] == "")
//            $input['totalprice'] = 0.0;
        $input['applicant_id'] = Auth::user()->id;


        $vendordeduction = Vendordeduction::create($input);
//        dd($vendordeduction);

        // create mcitempurchaseitems
        if (isset($vendordeduction))
        {
            $vendordeduction_items = json_decode($input['items_string']);
            foreach ($vendordeduction_items as $value) {
                if (strlen($value->itemname) > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['vendordeduction_id'] = $vendordeduction->id;
                    Vendordeductionitem::create($item_array);
                }
            }
        }
//        dd($vendordeduction);

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($vendordeduction))
        {
            $files = array_get($input,'files');
            $destinationPath = 'uploads/approval/vendordeduction/' . $vendordeduction->id . '/files/';
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
                        $vendordeductionattachment = new Vendordeductionattachment();
                        $vendordeductionattachment->vendordeduction_id = $vendordeduction->id;
                        $vendordeductionattachment->type = "file";
                        $vendordeductionattachment->filename = $originalName;
                        $vendordeductionattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $vendordeductionattachment->save();

                        array_push($fileattachments_url, url($destinationPath . $filename));
                        if (strcasecmp($extension, "pdf") == 0)
                            array_push($fileattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                        else
                        {
                            $filename2 = str_replace(".", "_", $filename);
                            array_push($fileattachments_url2, url("$destinationPath$filename2"));
                        }
//                    array_push($fileattachments_url2, url('mddauth/pdfjs-viewer') . "?file=" . "/$destinationPath$filename");


//                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
//                        url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
//                        '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', $paymentrequest,
//                        config('custom.dingtalk.agentidlist.approval'));
                    }
                }
            }
        }

        $image_urls = [];
        // create images in the desktop
        if ($vendordeduction)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/vendordeduction/' . $vendordeduction->id . '/images/';
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
                        $vendordeductionattachment = new Vendordeductionattachment();
                        $vendordeductionattachment->vendordeduction_id = $vendordeduction->id;
                        $vendordeductionattachment->type = "image";
                        $vendordeductionattachment->filename = $originalName;
                        $vendordeductionattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $vendordeductionattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($vendordeduction)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/vendordeduction/' . $vendordeduction->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/projectsitepurchase/' . $vendordeduction->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $vendordeductionattachment = new Vendordeductionattachment;
                $vendordeductionattachment->vendordeduction_id = $vendordeduction->id;
                $vendordeductionattachment->type = "image";     // add a '/' in the head.
                $vendordeductionattachment->path = "/$dir$filename";     // add a '/' in the head.
                $vendordeductionattachment->save();

                array_push($image_urls, $value);
            }
        }

        if (isset($vendordeduction))
        {
            $input['fileattachments_url'] = implode(" , ", $fileattachments_url2);
            $input['image_urls'] = json_encode($image_urls);
            $input['associatedapprovals'] = strlen($input['associatedapprovals']) > 0 ? json_encode(array($input['associatedapprovals'])) : "";
//            dd($input['associatedapprovals']);
            $input['approvers'] = $vendordeduction->approvers();
            $response = ApprovalController::vendordeduction($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $vendordeduction->forceDelete();
//                Log::info(json_encode($input));
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

                $vendordeduction->process_instance_id = $process_instance_id;
                $vendordeduction->business_id = $business_id;
                $vendordeduction->save();

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
        $vendordeduction = Vendordeduction::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($vendordeduction)
        {
            $vendordeduction->status = $status;
            $vendordeduction->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $vendordeduction = Vendordeduction::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($vendordeduction)
        {
            $vendordeduction->forceDelete();
        }
    }
}
