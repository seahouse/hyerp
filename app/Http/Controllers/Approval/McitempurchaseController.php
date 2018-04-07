<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Mcitempurchaseattachment;
use App\Models\Approval\Mcitempurchaseissuedrawing;
use App\Models\Approval\Mcitempurchaseitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Log, Storage;

class McitempurchaseController extends Controller
{
    private static $approvaltype_name = "制造中心物品申购";
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

        return view('approval/mcitempurchases/mcreate', compact('config'));
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
//        dd($input);
        $this->validate($request, [
            'manufacturingcenter'       => 'required',
            'itemtype'                    => 'required',
            'expirationdate'             => 'required',
            'sohead_id'                   => 'required|integer|min:1',
            'issuedrawing_values'       => 'required',
            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
        ]);
//        $input = HelperController::skipEmptyValue($input);

        // valid
        $weight_purchase = 0.0;
        $mcitempurchase_items = json_decode($input['items_string']);
        foreach ($mcitempurchase_items as $value) {
            if ($value->item_id > 0)
            {
                $weight_purchase += $value->weight;
            }
        }
        if ($weight_purchase <= 0.0)
            dd('申购重量不能为0');
        $weight_issuedrawing = 0.0;
        $issuedrawing_values = $input['issuedrawing_values'];
        foreach (explode(",", $issuedrawing_values) as $value) {
            if ($value > 0)
            {
                $issuedrawing = Issuedrawing::where('id', $value)->first();
                if (isset($issuedrawing))
                    $weight_issuedrawing += $issuedrawing->tonnage;
            }
        }
        if ($weight_purchase > $weight_issuedrawing)
            dd('申购重量超过了图纸重量');
        $weight_sohead_issuedrawing = 0.0;
        $weight_sohead_purchase = 0.0;
        $issuedrawings = Issuedrawing::where('sohead_id', $input['sohead_id'])->get();
        foreach ($issuedrawings as $issuedrawing)
        {
            $weight_sohead_issuedrawing += $issuedrawing->tonnage;
        }
        $mcitempurchases = Mcitempurchase::where('sohead_id', $input['sohead_id'])->get();
        foreach ($mcitempurchases as $mcitempurchase)
        {
            $weight_sohead_purchase += $mcitempurchase->mcitempurchaseitems->sum('weight');
        }
        if ($weight_sohead_purchase + $weight_purchase > $weight_sohead_issuedrawing)
            dd('该订单的申购重量之和超过了图纸重量之和');


        if ($input['totalprice'] == "")
            $input['totalprice'] = 0.0;
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

        $mcitempurchase = Mcitempurchase::create($input);
//        dd($mcitempurchase);

        // create mcitempurchaseitems
        $mcitempurchase_items = json_decode($input['items_string']);
        foreach ($mcitempurchase_items as $value) {
            if ($value->item_id > 0)
            {
                $item_array = json_decode(json_encode($value), true);
                $item_array['mcitempurchase_id'] = $mcitempurchase->id;
                Mcitempurchaseitem::create($item_array);
            }
        }

        // create mcitempurchaseissuedrawings
        $issuedrawing_values = $input['issuedrawing_values'];
        foreach (explode(",", $issuedrawing_values) as $value) {
            if ($value > 0)
            {
                Mcitempurchaseissuedrawing::create(array('mcitempurchase_id' => $mcitempurchase->id, 'issuedrawing_id' => $value));
            }
        }


        $image_urls = [];
        // create images in the desktop
        if ($mcitempurchase)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/mcitempurchase/' . $mcitempurchase->id . '/images/';
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
                        $mcitempurchaseattachment = new Mcitempurchaseattachment();
                        $mcitempurchaseattachment->mcitempurchase_id = $mcitempurchase->id;
                        $mcitempurchaseattachment->type = "image";
                        $mcitempurchaseattachment->filename = $originalName;
                        $mcitempurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $mcitempurchaseattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($mcitempurchase)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/mcitempurchase/' . $mcitempurchase->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/mcitempurchase/' . $mcitempurchase->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $mcitempurchaseattachment = new Mcitempurchaseattachment;
                $mcitempurchaseattachment->mcitempurchase_id = $mcitempurchase->id;
                $mcitempurchaseattachment->type = "image";     // add a '/' in the head.
                $mcitempurchaseattachment->path = "/$dir$filename";     // add a '/' in the head.
                $mcitempurchaseattachment->save();

                array_push($image_urls, url($destinationPath . $value));
            }
        }

        if (isset($mcitempurchase))
        {

            $input['image_urls'] = json_encode($image_urls);
            $input['approvers'] = $mcitempurchase->approvers();
            if ($input['approvers'] == "")
                $input['approvers'] = config('custom.dingtalk.default_approvers');       // wuceshi for test
            $response = ApprovalController::mcitempurchase($input);
            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->dingtalk_smartwork_bpms_processinstance_create_response->result->ding_open_errcode <> 0)
            {
                $mcitempurchase->forceDelete();
                Log::info(json_encode($input));
                dd('钉钉端创建失败: ' . $responsejson->dingtalk_smartwork_bpms_processinstance_create_response->result->error_msg);
            }
            else
            {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->dingtalk_smartwork_bpms_processinstance_create_response->result->process_instance_id;

                $response = DingTalkController::processinstance_get($process_instance_id);
                $responsejson = json_decode($response);
                $business_id = '';
                if ($responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->ding_open_errcode == 0)
                    $business_id = $responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->process_instance->business_id;

                $mcitempurchase->process_instance_id = $process_instance_id;
                $mcitempurchase->business_id = $business_id;
                $mcitempurchase->save();

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
