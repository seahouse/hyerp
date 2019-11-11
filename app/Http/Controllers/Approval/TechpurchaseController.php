<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Models\Approval\Techpurchase;
use App\Models\Approval\Techpurchaseattachment;
use App\Models\Approval\Techpurchaseitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class TechpurchaseController extends Controller
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
        return view('approval/techpurchases/mcreate', compact('config'));
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
//        dd($input);

        $this->validate($request, [
            'sohead_id'                   => 'required|integer|min:1',
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

        $techpurchase = Techpurchase::create($input);
//        dd($techpurchase);

        // create mcitempurchaseitems
        if (isset($techpurchase))
        {
            $techpurchase_items = json_decode($input['items_string']);
            foreach ($techpurchase_items as $value) {
                if (strlen($value->item_name) > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['techpurchase_id'] = $techpurchase->id;
                    Techpurchaseitem::create($item_array);
                }
            }
        }
//        dd($techpurchase);

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($techpurchase))
        {
            $files = array_get($input,'files');
            $destinationPath = 'uploads/approval/techpurchase/' . $techpurchase->id . '/files/';
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
                        $techpurchaseattachment = new Techpurchaseattachment();
                        $techpurchaseattachment->techpurchase_id = $techpurchase->id;
                        $techpurchaseattachment->type = "file";
                        $techpurchaseattachment->filename = $originalName;
                        $techpurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $techpurchaseattachment->save();

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
        if ($techpurchase)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/techpurchase/' . $techpurchase->id . '/images/';
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
                        $techpurchaseattachment = new Techpurchaseattachment();
                        $techpurchaseattachment->techpurchase_id = $techpurchase->id;
                        $techpurchaseattachment->type = "image";
                        $techpurchaseattachment->filename = $originalName;
                        $techpurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $techpurchaseattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($techpurchase)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/techpurchase/' . $techpurchase->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/techpurchase/' . $techpurchase->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $techpurchaseattachment = new Techpurchaseattachment();
                $techpurchaseattachment->techpurchase_id = $techpurchase->id;
                $techpurchaseattachment->type = "image";     // add a '/' in the head.
                $techpurchaseattachment->path = "/$dir$filename";     // add a '/' in the head.
                $techpurchaseattachment->save();

                array_push($image_urls, $value);
            }
        }

        if (isset($techpurchase))
        {
            $input['purchasecompany_name'] = $techpurchase->purchasecompany->name;
            $input['fileattachments_url'] = implode(" , ", $fileattachments_url2);
            $input['image_urls'] = json_encode($image_urls);
//            $input['associatedapprovals'] = strlen($input['associatedapprovals']) > 0 ? json_encode(array($input['associatedapprovals'])) : "";
//            dd($input['associatedapprovals']);
//            $input['approvers'] = $techpurchase->approvers();
            $response = ApprovalController::techpurchase($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $techpurchase->forceDelete();
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

                $techpurchase->process_instance_id = $process_instance_id;
                $techpurchase->business_id = $business_id;
                $techpurchase->save();

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
        $techpurchase = Techpurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if (isset($techpurchase))
        {
            $techpurchase->status = $status;
            $techpurchase->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $techpurchase = Techpurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($techpurchase)
        {
            $techpurchase->forceDelete();
        }
    }
}
