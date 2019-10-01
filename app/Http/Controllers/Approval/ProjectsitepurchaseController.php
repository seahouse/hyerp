<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Projectsitepurchase;
use App\Models\Approval\Projectsitepurchaseattachment;
use App\Models\Approval\Projectsitepurchaseitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Storage, Log;

class ProjectsitepurchaseController extends Controller
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

    public function getitemsbykey($key)
    {
        $query = Projectsitepurchase::where('business_id', $key)->orderBy('id', 'desc');
//        if ($customerid > 0)
//        {
//            $query->where('custinfo_id', $customerid);
//        }
//        $query->where(function ($query) use ($key) {
//            $query->where('number', 'like', '%'.$key.'%')
//                ->orWhere('descrip', 'like', '%'.$key.'%');
//        });
        $query->leftJoin('users', 'users.id', '=', 'projectsitepurchases.applicant_id');
        $query->leftJoin('hxcrm2016.dbo.vorder', 'vorder.id', '=', 'projectsitepurchases.sohead_id');
        $items = $query->select('projectsitepurchases.*', 'users.name as applicant', 'hxcrm2016.dbo.vorder.projectjc', 'hxcrm2016.dbo.vorder.number as sohead_number', 'hxcrm2016.dbo.vorder.salesmanager')->paginate(20);

        return $items;
        return response($items)
            ->header('Access-Control-Allow-Origin', 'http://www.huaxing-east.cn:2016');
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
        return view('approval/projectsitepurchases/mcreate', compact('config'));
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
//        dd($input);
//        $itemsArray = json_decode($input['items_string']);
//        if (is_array(json_decode($input['items_string2'])) && is_array(json_decode($input['items_string'])))
//            $itemsArray = array_merge(json_decode($input['items_string2']), json_decode($input['items_string']));
//        elseif (is_array(json_decode($input['items_string2'])) && !is_array(json_decode($input['items_string'])))
//            $itemsArray = json_decode($input['items_string2']);
//        $input['items_string'] = json_encode($itemsArray);
//        dd($itemsArray);
//        dd($input['items_string']);


//        $input = array(
//            '_token' => 'MXvSgAhoJ7JkDQ1f5zJvjbtMzdfZ4pePk9xE74Ud', 'manufacturingcenter' => '无锡制造中心机械车间', 'itemtype' => '消耗品类－如焊条', 'expirationdate' => '2018-04-16',
//            'project_name' => '厂部管理费用', 'sohead_id' => '7550', 'sohead_number' => 'JS-GC-00E-2016-04-0025', 'issuedrawing_numbers' => '', 'issuedrawing_values' => '', 'item_name' => '保温条',
//            'item_id' => '14818', 'item_spec' => 'φ32', 'unit' => 'm', 'unitprice' => '', 'quantity' => '12', 'weight' => '',
//            'items_string' => '[{"item_id":"14806","item_name":"PPR管","item_spec":"φ32","unit":"根","unitprice":0,"quantity":"3","weight":0},{"item_id":"14807","item_name":"PPR内丝直接","item_spec":"φ32 DN15","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14808","item_name":"PPR内丝直接","item_spec":"φ32 DN25","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14809","item_name":"PPR直接","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14810","item_name":"PPR大小头","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14811","item_name":"PPR球阀","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14812","item_name":"PPR弯头","item_spec":"","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14813","item_name":"PPR三通","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14814","item_name":"PPR三通","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14817","item_name":"PPR内丝直接","item_spec":"φ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14816","item_name":"管卡","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"20","weight":0},{"item_id":"14818","item_name":"保温条","item_spec":"φ32","unit":"m","unitprice":0,"quantity":"12","weight":0}]',
////            'items_string' => '[{"item_id":"14806","item_name":"PPR管","item_spec":"φ32","unit":"根","unitprice":0,"quantity":"3","weight":0},{"item_id":"14807","item_name":"PPR内丝直接","item_spec":"φ32 DN15","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14808","item_name":"PPR内丝直接","item_spec":"φ32 DN25","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14809","item_name":"PPR直接","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14810","item_name":"PPR大小头","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14811","item_name":"PPR球阀","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14812","item_name":"PPR弯头","item_spec":"","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14813","item_name":"PPR三通","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14814","item_name":"PPR三通","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14817","item_name":"PPR内丝直接","item_spec":"φ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14816","item_name":"管卡","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"20","weight":0},{"item_id":"14818","item_name":"保温条","item_spec":"φ32","unit":"m","unitprice":0,"quantity":"12","weight":0}]',
//            'totalprice' => '0', 'detailuse' => '上述材料问雾化器研发中心用', 'applicant_id' => '38', 'approversetting_id' => '-1', 'images' => array(null),
//            'approvers' => 'manager1200');

        $this->validate($request, [
            'sohead_id'                   => 'required|integer|min:1',
            'purchasetype'               => 'required',
            'purchasereason'             => 'required',
//            'issuedrawing_values'       => 'required',
            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
        ]);
//        $input = HelperController::skipEmptyValue($input);

//        // valid
//        $weight_purchase = 0.0;
//        $mcitempurchase_items = json_decode($input['items_string']);
//        foreach ($mcitempurchase_items as $value) {
//            if ($value->item_id > 0)
//            {
//                $weight_purchase += $value->weight;
//            }
//        }
//        $input['totalweight'] = $weight_purchase;
//
//        if ($input['sohead_id'] <> "7550")
//        {
//            $weight_issuedrawing = 0.0;
//            $issuedrawing_values = $input['issuedrawing_values'];
//            foreach (explode(",", $issuedrawing_values) as $value) {
//                if ($value > 0)
//                {
//                    $issuedrawing = Issuedrawing::where('id', $value)->first();
//                    if (isset($issuedrawing))
//                        $weight_issuedrawing += $issuedrawing->tonnage;
//                }
//            }
//            if ($weight_purchase > $weight_issuedrawing * 1.3)
//                dd('申购重量超过了图纸重量');
//            $weight_sohead_issuedrawing = 0.0;
//            $weight_sohead_purchase = 0.0;
//            $issuedrawings = Issuedrawing::where('sohead_id', $input['sohead_id'])->where('status', 0)->get();
//            foreach ($issuedrawings as $issuedrawing)
//            {
//                $weight_sohead_issuedrawing += $issuedrawing->tonnage;
//            }
//            $mcitempurchases = Mcitempurchase::where('sohead_id', $input['sohead_id'])->where('status', '>=', 0)->get();
//            foreach ($mcitempurchases as $mcitempurchase)
//            {
//                $weight_sohead_purchase += $mcitempurchase->mcitempurchaseitems->sum('weight');
//            }
//            if (($weight_sohead_purchase + $weight_purchase)  > $weight_sohead_issuedrawing * 1.2)
//                dd("该订单的申购重量之和超过了图纸重量之和。订单下图单总重量：" . $weight_sohead_issuedrawing . "，已申购：" . $weight_sohead_purchase . "，此次申购：" . $weight_purchase . "。");
//        }

        if ($input['totalprice'] == "")
            $input['totalprice'] = 0.0;
        $input['applicant_id'] = Auth::user()->id;

//        // set approversetting_id
//        $approvaltype_id = self::typeid();
//        if ($approvaltype_id > 0)
//        {
//            $approversettingFirst = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->first();
//            if ($approversettingFirst)
//                $input['approversetting_id'] = $approversettingFirst->id;
//            else
//                $input['approversetting_id'] = -1;
//        }
//        else
//            $input['approversetting_id'] = -1;

        $projectsitepurchase = Projectsitepurchase::create($input);
//        dd($projectsitepurchase);

        // create mcitempurchaseitems
        if (isset($projectsitepurchase))
        {
            $projectsitepurchase_items = json_decode($input['items_string']);
            foreach ($projectsitepurchase_items as $value) {
                if ($value->item_id > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['projectsitepurchase_id'] = $projectsitepurchase->id;
                    Projectsitepurchaseitem::create($item_array);
                }
            }
        }
//        dd($projectsitepurchase);
        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($projectsitepurchase))
        {
            $files = array_get($input,'files');
            $destinationPath = 'uploads/approval/projectsitepurchase/' . $projectsitepurchase->id . '/files/';
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
                        $projectsitepurchaseattachment = new Projectsitepurchaseattachment();
                        $projectsitepurchaseattachment->projectsitepurchase_id = $projectsitepurchase->id;
                        $projectsitepurchaseattachment->type = "file";
                        $projectsitepurchaseattachment->filename = $originalName;
                        $projectsitepurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $projectsitepurchaseattachment->save();

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
        if ($projectsitepurchase)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/projectsitepurchase/' . $projectsitepurchase->id . '/images/';
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
                        $projectsitepurchaseattachment = new Projectsitepurchaseattachment();
                        $projectsitepurchaseattachment->projectsitepurchase_id = $projectsitepurchase->id;
                        $projectsitepurchaseattachment->type = "image";
                        $projectsitepurchaseattachment->filename = $originalName;
                        $projectsitepurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $projectsitepurchaseattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($projectsitepurchase)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/projectsitepurchase/' . $projectsitepurchase->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/projectsitepurchase/' . $projectsitepurchase->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $projectsitepurchaseattachment = new Projectsitepurchaseattachment;
                $projectsitepurchaseattachment->projectsitepurchase_id = $projectsitepurchase->id;
                $projectsitepurchaseattachment->type = "image";     // add a '/' in the head.
                $projectsitepurchaseattachment->path = "/$dir$filename";     // add a '/' in the head.
                $projectsitepurchaseattachment->save();

                array_push($image_urls, $value);
            }
        }
//        dd($projectsitepurchase);

        if (isset($projectsitepurchase))
        {
            $input['totalprice'] = $projectsitepurchase->projectsitepurchaseitems->sum('price') + $input['freight'];
            $input['image_urls'] = json_encode($image_urls);
            $input['approvers'] = $projectsitepurchase->approvers();
            $response = ApprovalController::projectsitepurchase($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->result->ding_open_errcode <> 0)
            {
                $projectsitepurchase->forceDelete();
//                Log::info(json_encode($input));
                dd('钉钉端创建失败: ' . $responsejson->result->error_msg);
            }
            else
            {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->result->process_instance_id;

                $response = DingTalkController::processinstance_get($process_instance_id);
                $responsejson = json_decode($response);
                $business_id = '';
                if ($responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->ding_open_errcode == 0)
                    $business_id = $responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->process_instance->business_id;

                $projectsitepurchase->process_instance_id = $process_instance_id;
                $projectsitepurchase->business_id = $business_id;
                $projectsitepurchase->save();

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
        $projectsitepurchase = Projectsitepurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($projectsitepurchase)
        {
            $projectsitepurchase->status = $status;
            $projectsitepurchase->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $projectsitepurchase = Projectsitepurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($projectsitepurchase)
        {
            $projectsitepurchase->forceDelete();
        }
    }
}
