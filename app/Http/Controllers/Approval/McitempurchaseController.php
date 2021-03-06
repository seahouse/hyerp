<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\HelperController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Mcitempurchaseattachment;
use App\Models\Approval\Mcitempurchaseissuedrawing;
use App\Models\Approval\Mcitempurchaseitem;
use App\Models\Product\Itemp_hxold;
use App\Models\Product\Unit_hxold;
use App\Models\Purchase\Poitem_hx;
use App\Models\Purchase\Prhead;
use App\Models\Purchase\Pritem;
use App\Models\Purchase\Purchaseorder_hx;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Userold;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Auth, Log, Storage, Excel;

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
        self::updateStatusByProcessInstanceId('b66b0474-3a2b-40bd-88c7-d4fc8f77506b', 0);
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
        $itemsArray = json_decode($input['items_string']);
        if (is_array(json_decode($input['items_string2'])) && is_array(json_decode($input['items_string'])))
            $itemsArray = array_merge(json_decode($input['items_string2']), json_decode($input['items_string']));
        elseif (is_array(json_decode($input['items_string2'])) && !is_array(json_decode($input['items_string'])))
            $itemsArray = json_decode($input['items_string2']);
        $input['items_string'] = json_encode($itemsArray);
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
            'manufacturingcenter'       => 'required',
            'itemtype'                    => 'required',
            'expirationdate'             => 'required',
            'sohead_id'                   => 'required|integer|min:1',
//            'issuedrawing_values'       => 'required',
            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
            'detailuse'               => 'required',
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
        $input['totalweight'] = $weight_purchase;
//        if ($weight_purchase <= 0.0)
//            dd('申购重量不能为0');

        if ($input['sohead_id'] <> "7550")
        {
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
            if ($weight_purchase > $weight_issuedrawing * 1.3)
                dd('申购重量超过了图纸重量');
            $weight_sohead_issuedrawing = 0.0;
            $weight_sohead_purchase = 0.0;
            $issuedrawings = Issuedrawing::where('sohead_id', $input['sohead_id'])->where('status', 0)->get();
            foreach ($issuedrawings as $issuedrawing)
            {
                $weight_sohead_issuedrawing += $issuedrawing->tonnage;
            }
            $mcitempurchases = Mcitempurchase::where('sohead_id', $input['sohead_id'])->where('status', '>=', 0)->get();
            foreach ($mcitempurchases as $mcitempurchase)
            {
                $weight_sohead_purchase += $mcitempurchase->mcitempurchaseitems->sum('weight');
            }
            if (($weight_sohead_purchase + $weight_purchase)  > $weight_sohead_issuedrawing * 1.2)
                dd("该订单的申购重量之和超过了图纸重量之和。订单下图单总重量：" . $weight_sohead_issuedrawing . "，已申购：" . $weight_sohead_purchase . "，此次申购：" . $weight_purchase . "。");
        }

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

        // generate issuedrawingweight and issuedrawingoverviews field
        $issuedrawing_values = $input['issuedrawing_values'];
        $issuedrawing_weights = [];
        $issuedrawing_overviews = [];
        foreach (explode(",", $issuedrawing_values) as $value) {
            if ($value > 0)
            {
                Mcitempurchaseissuedrawing::create(array('mcitempurchase_id' => $mcitempurchase->id, 'issuedrawing_id' => $value));
                $issuedrawing = Issuedrawing::where('id', $value)->first();
                if (isset($issuedrawing))
                {
                    array_push($issuedrawing_weights, $issuedrawing->tonnage);
                    array_push($issuedrawing_overviews, $issuedrawing->overview);
                }
            }
        }
        $input['issuedrawing_weights'] = implode("+", $issuedrawing_weights) . "=" . array_sum($issuedrawing_weights);
        $input['issuedrawing_overviews'] = implode("\n", $issuedrawing_overviews);

        $projecttonnagetotal = Issuedrawing::where('sohead_id', $input['sohead_id'])->where('status', 0)->sum('tonnage');
        $mcitempurchaseidlist = Mcitempurchase::where('sohead_id', $input['sohead_id'])->where('status', 0)->pluck('id');
        $projecttonnagedonetotal = Mcitempurchaseitem::whereIn('mcitempurchase_id', $mcitempurchaseidlist)->sum('weight');
        $projectcabinettotal = 0.0;
        foreach (Issuedrawing::where('sohead_id', $input['sohead_id'])->where('status', 0)->get() as $issuedrawing) {
            $projectcabinettotal += $issuedrawing->issuedrawingcabinets->sum('quantity');
        }
        $input['projecttonnage'] = '项目总吨数' . $projecttonnagetotal . '吨，已申购' . $projecttonnagedonetotal . "吨。\n" .
            "项目电气柜体总数" . $projectcabinettotal . "。" ;

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if ($mcitempurchase)
        {
            $files = array_get($input,'files');
            $destinationPath = 'uploads/approval/mcitempurchase/' . $mcitempurchase->id . '/files/';
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
                    $mcitempurchaseattachment = new Mcitempurchaseattachment();
                    $mcitempurchaseattachment->mcitempurchase_id = $mcitempurchase->id;
                    $mcitempurchaseattachment->type = "file";
                    $mcitempurchaseattachment->filename = $originalName;
                    $mcitempurchaseattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $mcitempurchaseattachment->save();

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
            $input['fileattachments_url'] = implode(" , ", $fileattachments_url2);
            $input['image_urls'] = json_encode($image_urls);
            $input['approvers'] = $mcitempurchase->approvers();
            $response = ApprovalController::mcitempurchase($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->result->ding_open_errcode <> 0)
            {
                $mcitempurchase->forceDelete();
//                Log::info(json_encode($input));
                dd('钉钉端创建失败: ' . $responsejson->result->error_msg);
            }
            else
            {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->result->process_instance_id;

                if ($input['syncdtdesc'] == "许昌")
                    $response = DingTalkController::processinstance_get2($process_instance_id);
                else
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

    public function uploadparseexcel(Request $request)
    {
        //
        $input = $request->all();
//        dd($input);

        $file = array_get($input,'items_excelfile');
//        Log::info($file);
//        dd($file);
        if ($file)
        {
            $destinationPath = 'uploads/approval/mcitempurchase/itemsexcel/';
            $originalName = $file->getClientOriginalName();         // aa.xlsx
            $extension = $file->getClientOriginalExtension();       // .xlsx
//            Log::info('extension: ' . $extension);
            $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
            Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));
            $upload_success = $file->move($destinationPath, $filename);

//            dd(Storage::get($destinationPath . $filename));
//            echo asset($destinationPath . $filename);
//            dd(Storage::url($destinationPath . $filename));
//            dd(file_get_contents(Storage::disk()->url($destinationPath . $filename)));

            $excel = [];
            Excel::load($destinationPath . $filename, function ($reader) use (&$excel) {
                $objExcel = $reader->getExcel();
                $sheet = $objExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                //  Loop through each row of the worksheet in turn
                for ($row = 1; $row <= $highestRow; $row++)
                {
                    //  Read a row of data into an array
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                        NULL, TRUE, FALSE);

                    $excel[] = $rowData[0];
                }

//                $reader->each(function($sheet) {
//                    // Loop through all rows
//                    $sheet->each(function($row) {
//                        dd($row->firstname);
//                    });
//                });

//                $data = $reader->first();
//                dd($data);
            });

        }

        $items = [];
        foreach ($excel as $key => $row)
        {
            if ($key == 0) continue;
            $name = $row[1];
            $spec = $row[2];
            if ($name <> "")
            {
                $query = Itemp_hxold::where('goods_name', $name);
                if ($spec <> "")
                {
                    $query->where('goods_spec', $spec);
                }
                $item = $query->first();
                if (isset($item))
                {
                    $unit_id = '';
                    $unit_name = isset($row[4]) ? $row[4] : '';
                    if (strlen($unit_name) > 0)
                    {
                        $unit_hxold = Unit_hxold::where('name', $unit_name)->first();
                        if (isset($unit_hxold))
                            $unit_id = $unit_hxold->id;
                    }
                    
                    $itemArray = [
                        'item_id'       => $item->goods_id,
                        'item_name'     => $item->goods_name,
                        'item_spec'     => $item->goods_spec,
                        'unit'           => $item->goods_unit_name,
                        'size'           => isset($row[3]) ? $row[3] : '',
                        'material'      => isset($row[8]) ? $row[8] : '',
                        'unitprice'     => 0.0,
                        'quantity'      => isset($row[5]) ? $row[5] : 0.0,
                        'unit_id'       => $unit_id,
                        'unit_name'     => strlen($unit_id) > 0 ?  $unit_name : '',
                        'weight'        => isset($row[6]) ? $row[6] : 0.0,
                        'remark'        => isset($row[7]) ? $row[7] : '',
                    ];
                    array_push($items, $itemArray);
                }
            }
        }
//        dd($items);
        return response()->json($items);






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

    public static function updateStatusByProcessInstanceId($processInstanceId, $status)
    {
        $mcitempurchase = Mcitempurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($mcitempurchase)
        {
            $mcitempurchase->status = $status;
            $mcitempurchase->save();

            // 如果是审批完成且通过，则创建采购申请单
            if ($status == 0)
            {
                $cp = 'WX';
                $purchasecompany_id = 1;
                if ($mcitempurchase->manufacturingcenter == "宣城制造中心")
                {
                    $cp = 'AH';
                    $purchasecompany_id = 2;
                }
                elseif ($mcitempurchase->purchasecompany_id == "许昌制造中心")
                {
                    $cp = 'HN';
                    $purchasecompany_id = 3;
                }
                elseif ($mcitempurchase->purchasecompany_id == "中易新材料")
                {
                    $cp = 'ZY';
                    $purchasecompany_id = 4;
                }

                $mcitempurchaseitem = $mcitempurchase->mcitempurchaseitems->first();
                $item_index = '';
                if (isset($mcitempurchaseitem))
                {
                    $item_index = HelperController::pinyin_long($mcitempurchaseitem->item->goods_name);
                }
                $item_index = strlen($item_index) > 0 ? $item_index : 'spmc';
                if (strlen($item_index) < 4)
                    $item_index = str_pad($item_index, 4, 0, STR_PAD_LEFT);
                elseif (strlen($item_index) > 4)
                    $item_index = substr($item_index, 0, 4);
                $seqnumber = Purchaseorder_hx::where('编号年份', Carbon::today()->year)->max('编号数字');
                $seqnumber += 1;
                $seqnumber = str_pad($seqnumber, 4, 0, STR_PAD_LEFT);

                $userold_id = 0;
                $userold = Userold::where('user_id', $mcitempurchase->applicant_id)->first();
                if (isset($userold))
                    $userold_id = $userold->user_hxold_id;

                $pohead_number = $cp . '-' . $item_index . '-' . Carbon::today()->format('Y-m') . '-' . $seqnumber;

//                $techpurchaseattachment_techspecification = $mcitempurchase->techpurchaseattachments->where('type', 'techspecification')->first();

                $sohead_name = '';
                $sohead = Salesorder_hxold::find($mcitempurchase->sohead_id);
                if (isset($sohead))
                    $sohead_name = $sohead->number . "|" . $sohead->custinfo_name . "|" . $sohead->descrip . "|" . $sohead->amount;

//                $data = [
//                    'purchasecompany_id'    => $purchasecompany_id,
//                    '采购订单编号'            => $pohead_number,
//                    '申请人ID'                => $userold_id,
//                    '对应项目ID'              => $mcitempurchase->sohead_id,
//                    '项目名称'                => $sohead_name,
//                    '申请到位日期'            => $mcitempurchase->expirationdate,
//                    '修造或工程'             => $cp,
////                    '技术规范书'             => isset($techpurchaseattachment_techspecification) ? $techpurchaseattachment_techspecification->filename : '',
//                    '编号年份'                => Carbon::today()->year,
//                    '编号数字'                => $seqnumber,
//                    '编号商品名称'            => $item_index,
//                    'type'                    => '生产',
//                    'business_id'            => $mcitempurchase->business_id,
//                ];
//                $pohead = Purchaseorder_hx::create($data);
//
//                if (isset($pohead))
//                {
//                    foreach ($mcitempurchase->mcitempurchaseitems as $mcitempurchaseitem)
//                    {
//                        $item = Itemp_hxold::where('goods_id', $mcitempurchaseitem->item_id)->first();
//                        if (isset($item))
//                        {
//                            $data = [
//                                'order_id'      => $pohead->id,
//                                'goods_id'      => $mcitempurchaseitem->item_id,
//                                'goods_name'    => $item->goods_name,
//                                'goods_number'  => $mcitempurchaseitem->quantity,
//                                'goods_unit'    => $item->goods_unit_name,
//                            ];
//                            Poitem_hx::create($data);
//                        }
//                    }
//
////                    // 拷贝“技术规范书”到对应的ERP目录下
////                    if (isset($techpurchaseattachment_techspecification))
////                    {
////                        // 将中文的字段名称转换后使用
////                        $pohead_id_key = iconv("UTF-8","GBK//IGNORE", '采购订单ID');
////                        $dir = config('custom.hxold.purchase_techspecification_dir') . $pohead->$pohead_id_key . "/";
////                        if (!is_dir($dir)) {
////                            mkdir($dir);
////                        }
////                        $dest = iconv("UTF-8","GBK//IGNORE", $dir . $techpurchaseattachment_techspecification->filename);
////                        copy(public_path($techpurchaseattachment_techspecification->path), $dest);
////                    }
//                }

                $number = 'PR' . Carbon::today()->format('Ymd');
                $dayseq = Prhead::where('number', 'like', $number . '%')->max('dayseq');
                if (isset($dayseq))
                    $dayseq++;
                else
                    $dayseq = 1;
                $number .= str_pad($dayseq, 4, 0, STR_PAD_LEFT);

                // 新的采购申请单
                $data = [
                    'number'                 => $number,
                    'dayseq'                 => $dayseq,
                    'company_id'            => $purchasecompany_id,
                    'sohead_id'              => $mcitempurchase->sohead_id,
                    'type'                    => '生产',
                    'applicant_id'          => $mcitempurchase->applicant_id,
//                    '申请到位日期'            => $mcitempurchase->expirationdate,
//                    '技术规范书'             => isset($techpurchaseattachment_techspecification) ? $techpurchaseattachment_techspecification->filename : '',
                    'approval_type'         => 'mcitempurchase',
                    'process_instance_id'  => $processInstanceId,
                ];
                $prhead = Prhead::create($data);

                if (isset($prhead))
                {
                    foreach ($mcitempurchase->mcitempurchaseitems as $mcitempurchaseitem)
                    {
                        $item = Itemp_hxold::where('goods_id', $mcitempurchaseitem->item_id)->first();
                        if (isset($item))
                        {
                            $data = [
                                'prhead_id'      => $prhead->id,
                                'item_id'      => $mcitempurchaseitem->item_id,
                                'quantity'      => $mcitempurchaseitem->quantity,
                            ];
                            Pritem::create($data);
                        }
                    }

//                    // 拷贝“技术规范书”到对应的ERP目录下
//                    if (isset($techpurchaseattachment_techspecification))
//                    {
//                        // 将中文的字段名称转换后使用
//                        $pohead_id_key = iconv("UTF-8","GBK//IGNORE", '采购订单ID');
//                        $dir = config('custom.hxold.purchase_techspecification_dir') . $prhead->$pohead_id_key . "/";
//                        if (!is_dir($dir)) {
//                            mkdir($dir);
//                        }
//                        $dest = iconv("UTF-8","GBK//IGNORE", $dir . $techpurchaseattachment_techspecification->filename);
//                        copy(public_path($techpurchaseattachment_techspecification->path), $dest);
//                    }
                }
            }
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $mcitempurchase = Mcitempurchase::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($mcitempurchase)
        {
            $mcitempurchase->forceDelete();
        }
    }
}
