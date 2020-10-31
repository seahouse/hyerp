<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Models\Approval\Additionsalesorder;
use App\Models\Approval\Additionsalesorderattachment;
use App\Models\Approval\Additionsalesorderitem;
use App\Models\Sales\Equipmenttypeass_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Salesorder_hxold_t;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Storage, Carbon, Log;

class AdditionsalesorderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        self::updateStatusByProcessInstanceId('ttttt', 0);
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
        return view('approval/additionsalesorders/mcreate', compact('config'));
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
//        $input['associatedapprovals'] = strlen($input['associatedapprovals']) > 0 ? json_encode(array($input['associatedapprovals'])) : "";
//        dd($input['associatedapprovals']);

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
//            'amounttype'               => 'required',
//            'supplier_id'             => 'required',
//            'issuedrawing_values'       => 'required',
//            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
//            'associated_approval_projectpurchase'            => 'required',
        ]);
//        dd($input);
//        $input = HelperController::skipEmptyValue($input);


        $inputs['applicant_id'] = Auth::user()->id;


        $additionsalesorder = Additionsalesorder::create($inputs);
//        dd($additionsalesorder);

        // create $additionsalesorderitems
        if (isset($additionsalesorder))
        {
            $additionsalesorder_items = json_decode($inputs['items_string']);
            foreach ($additionsalesorder_items as $value) {
                if (strlen($value->type) > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['additionsalesorder_id'] = $additionsalesorder->id;
                    Additionsalesorderitem::create($item_array);
                }
            }
        }

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($additionsalesorder))
        {
            $files = array_get($inputs,'files');
            $destinationPath = 'uploads/approval/additionsalesorder/' . $additionsalesorder->id . '/files/';
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
                        $additionsalesorderattachment = new Additionsalesorderattachment();
                        $additionsalesorderattachment->additionsalesorder_id = $additionsalesorder->id;
                        $additionsalesorderattachment->type = "file";
                        $additionsalesorderattachment->filename = $originalName;
                        $additionsalesorderattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $additionsalesorderattachment->save();

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
        if ($additionsalesorder)
        {
            $files = array_get($inputs,'images');
            $destinationPath = 'uploads/approval/additionsalesorder/' . $additionsalesorder->id . '/images/';
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
                        $additionsalesorderattachment = new Additionsalesorderattachment();
                        $additionsalesorderattachment->additionsalesorder_id = $additionsalesorder->id;
                        $additionsalesorderattachment->type = "image";
                        $additionsalesorderattachment->filename = $originalName;
                        $additionsalesorderattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $additionsalesorderattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($additionsalesorder)
        {
            $images = array_where($inputs, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/additionsalesorder/' . $additionsalesorder->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/projectsitepurchase/' . $additionsalesorder->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $projectsitepurchaseattachment->additionsalesorder_id = $additionsalesorder->id;
                $projectsitepurchaseattachment->type = "image";     // add a '/' in the head.
                $projectsitepurchaseattachment->path = "/$dir$filename";     // add a '/' in the head.
                $projectsitepurchaseattachment->save();

                array_push($image_urls, $value);
            }
        }
//        dd($additionsalesorder);

        if (isset($additionsalesorder))
        {
            $inputs['totalamount'] = $additionsalesorder->additionsalesorderitems->sum('amount');
            $inputs['image_urls'] = json_encode($image_urls);
//            $inputs['approvers'] = $additionsalesorder->approvers();
            $response = ApprovalController::additionsalesorder($inputs);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $additionsalesorder->forceDelete();
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

                $additionsalesorder->process_instance_id = $process_instance_id;
                $additionsalesorder->business_id = $business_id;
                $additionsalesorder->save();

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
        $additionsalesorder = Additionsalesorder::where('process_instance_id', $processInstanceId)->firstOrFail();
        if (isset($additionsalesorder))
        {
            $additionsalesorder->status = $status;
            $additionsalesorder->save();

            // 如果是审批完成且通过，则创建老系统中的销售订单
            if ($status == 0)
            {
                $sohead_parent = $additionsalesorder->sohead;
                if (isset($sohead_parent))
                {
                    $company_name = $sohead_parent->company_name;

                    $sohead_number = $sohead_parent->province_name . '-';
                    $sohead_number .= mb_substr($sohead_parent->company_name, 0, 2) . '-';
                    $equipmenttype_chars = '';
                    foreach ($sohead_parent->equipmenttypes as $equipmenttype)
                        $equipmenttype_chars .= $equipmenttype->equipmenttype_char;
                    $equipmenttype_chars = str_pad($equipmenttype_chars, 3, '0', STR_PAD_LEFT);
                    $sohead_number .= $equipmenttype_chars . '-';
                    $sohead_number .= Carbon\Carbon::today()->format('Y-m-');
                    $sohead_number = HelperController::pinyin_long($sohead_number);
                    $ordercount = Salesorder_hxold_t::where('订货日期', '>=', Carbon\Carbon::create(Carbon\Carbon::today()->year, 1, 1))->where('订货日期', '<', Carbon\Carbon::create(Carbon\Carbon::today()->year + 1, 1, 1))->count();
                    $ordercount = $ordercount + 3;
                    $sohead_number .= str_pad($ordercount, 4, '0', STR_PAD_LEFT);
                    Log::info($sohead_number);

                    $amount = $additionsalesorder->additionsalesorderitems->sum('amount') / 10000 ;

                    $data = [
                        '接单公司名称'            => $company_name,
                        '客户ID'                  => $sohead_parent->custinfo_id,
                        '客户联系人ID'            => $sohead_parent->customer_contact_id,
                        '工程所在省市ID'          => $sohead_parent->project_city_id,
                        '工程名称'                => $sohead_parent->descrip,
                        'projectjc'              => $sohead_parent->projectjc,
                        '订货日期'                => Carbon\Carbon::today(),
                        '交货日期'                => Carbon\Carbon::today()->addMonth(),
                        '销售经理ID'              => $sohead_parent->salesmanager_id,
                        '编号'                    => $sohead_number,
                    ];
                    $sohead = new Salesorder_hxold_t();
                    $sohead->接单公司名称 = $company_name;
                    $sohead->客户ID = $sohead_parent->custinfo_id;
                    $sohead->客户联系人ID = $sohead_parent->customer_contact_id;
                    $sohead->工程所在省市ID = $sohead_parent->project_city_id;
                    $sohead->工程名称 = $sohead_parent->descrip;
                    $sohead->projectjc = $sohead_parent->projectjc;
                    $sohead->订货日期 = Carbon\Carbon::today();
                    $sohead->交货日期 = Carbon\Carbon::today()->addMonth();
                    $sohead->销售经理ID = $sohead_parent->salesmanager_id;
                    $sohead->订单编号 = $sohead_number;
                    $sohead->订单金额 = $amount;
                    $sohead->associated_approval_type = 'additionsalesorder';
                    $sohead->associated_process_instance_id = $processInstanceId;
                    $sohead->save();

                    if (isset($sohead))
                    {
                        foreach ($sohead_parent->equipmenttypeasses as $equipmenttypeass)
                        {
                            $data = [
                                'equipmenttypeass_order_id'      => $sohead->订单ID,
                                'equipmenttypeass_equipmenttype_id'      => $equipmenttypeass->equipmenttypeass_equipmenttype_id,
                            ];
                            Equipmenttypeass_hxold::create($data);
                        }

//                        // 拷贝“技术规范书”到对应的ERP目录下
//                        if (isset($techpurchaseattachment_techspecification))
//                        {
//                            // 将中文的字段名称转换后使用
//                            $pohead_id_key = iconv("UTF-8","GBK//IGNORE", '采购订单ID');
//                            $dir = config('custom.hxold.purchase_techspecification_dir') . $pohead->$pohead_id_key . "/";
//                            if (!is_dir($dir)) {
//                                mkdir($dir);
//                            }
//                            $dest = iconv("UTF-8","GBK//IGNORE", $dir . $techpurchaseattachment_techspecification->filename);
//                            copy(public_path($techpurchaseattachment_techspecification->path), $dest);
//                        }

                        // 发送钉钉消息给 Zhang Junye
                        $touser = User::where('email', "zhangjunye@huaxing-east.com")->first();
                        if (isset($touser))
                        {
                            $msg = '根据销售增补审批单自动生成销售订单。销售增补单编号：' . $additionsalesorder->business_id . '，生成的销售订单编号：' . $sohead_number . '。';
                            $data = [
                                'userid'        => $touser->id,
                                'msgcontent'    => urlencode($msg) ,
                            ];

                            DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                        }
                    }
                }
            }
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $additionsalesorder = Additionsalesorder::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($additionsalesorder)
        {
            $additionsalesorder->forceDelete();
        }
    }
}
