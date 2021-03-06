<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceGetRequest;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Corporatepayment;
use App\Models\Approval\Corporatepaymentattachment;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestattachment;
use App\Models\Approval\Projectsitepurchase;
use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Purchase\Vendinfo_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Dtuser;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Log, Storage;

class CorporatepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $client = new DingTalkClient();
//        $req = new OapiProcessinstanceGetRequest();
//        $req->setProcessInstanceId('f808e9fb-0197-44ba-b12b-32d2a5ae2875');
//        $accessToken = DingTalkController::getAccessToken();
//        $response = $client->execute($req, $accessToken);
//        $response = json_decode(json_encode($response, JSON_UNESCAPED_UNICODE));
//        $operation_records = $response->process_instance->operation_records->operation_records_vo;
//        $dtuser_whl = Dtuser::where('user_id', 2)->first();
//        foreach ($operation_records as $operation_record)
//        {
//            if ($operation_record->operation_type == 'ADD_REMARK')
//            {
//                dd($operation_record);
//            }
//        }
//        dd($response->process_instance->operation_records);
//        dd(json_encode($response, JSON_UNESCAPED_UNICODE));

        $this->updateStatusByProcessInstanceId('4612d80f-d818-4f59-a3b9-8b22f25bbedc', 0);
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
        return view('approval/corporatepayments/mcreate', compact('config'));
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
//        dd($request->has('paidpercent'));
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
//            'sohead_id'                   => 'required|integer|min:1',
            'amounttype'               => 'required',
            'supplier_id'              => 'required',
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

        if (!$request->has('sohead_id') || ($request->has('sohead_id') && $request->input('sohead_id') <= 0))
        {
            if ($request->has('sohead_number') && strlen($request->input('sohead_number')))
            {
                $sohead = Salesorder_hxold::where('number', $request->input('sohead_number'))->first();
                if (isset($sohead))
                    $inputs['sohead_id'] = $sohead->id;
            }
        }

        if (!$request->has('pohead_id') || ($request->has('pohead_id') && $request->input('pohead_id') <= 0))
        {
            if ($request->has('pohead_number') && strlen($request->input('pohead_number')))
            {
                $pohead = Purchaseorder_hxold::where('number', $request->input('pohead_number'))->first();
                if (isset($pohead))
                    $inputs['pohead_id'] = $pohead->id;
            }
        }

        if (!$request->has('supplier_id') || ($request->has('supplier_id') && $request->input('supplier_id') <= 0))
        {
            if ($request->has('supplier_name') && strlen($request->input('supplier_name')))
            {
                $supplier = Vendinfo_hxold::where('name', $request->input('supplier_name'))->first();
                if (isset($supplier))
                    $inputs['supplier_id'] = $supplier->id;
            }
        }

        if (!$request->has('amount')) $inputs['amount'] = 0;
        if (!$request->has('ticketedpercent')) $inputs['ticketedpercent'] = 0;
        if (!$request->has('paidpercent')) $inputs['paidpercent'] = 0;
        if (!$request->has('amountpercent')) $inputs['amountpercent'] = 0;

        $inputs['applicant_id'] = Auth::user()->id;

        $inputs['associated_approval_projectpurchase'] = strlen($inputs['associated_approval_projectpurchase']) > 0 ? json_encode(array($inputs['associated_approval_projectpurchase'])) : "";
//        dd($inputs['associated_approval_projectpurchase']);
        $corporatepayment = Corporatepayment::create($inputs);
//        dd($corporatepayment);

//        // create mcitempurchaseitems
//        if (isset($corporatepayment))
//        {
//            $projectsitepurchase_items = json_decode($inputs['items_string']);
//            foreach ($projectsitepurchase_items as $value) {
//                if ($value->item_id > 0)
//                {
//                    $item_array = json_decode(json_encode($value), true);
//                    $item_array['projectsitepurchase_id'] = $projectsitepurchase->id;
//                    Projectsitepurchaseitem::create($item_array);
//                }
//            }
//        }

        // create files
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($corporatepayment))
        {
            $files = array_get($inputs,'files');
            $destinationPath = 'uploads/approval/corporatepayment/' . $corporatepayment->id . '/files/';
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
                        $corporatepaymentattachment = new Corporatepaymentattachment();
                        $corporatepaymentattachment->corporatepayment_id = $corporatepayment->id;
                        $corporatepaymentattachment->type = "file";
                        $corporatepaymentattachment->filename = $originalName;
                        $corporatepaymentattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $corporatepaymentattachment->save();

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
        if ($corporatepayment)
        {
            $files = array_get($inputs,'images');
            $destinationPath = 'uploads/approval/corporatepayment/' . $corporatepayment->id . '/images/';
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
                        $corporatepaymentattachment = new Corporatepaymentattachment();
                        $corporatepaymentattachment->corporatepayment_id = $corporatepayment->id;
                        $corporatepaymentattachment->type = "image";
                        $corporatepaymentattachment->filename = $originalName;
                        $corporatepaymentattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $corporatepaymentattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($corporatepayment)
        {
            $images = array_where($inputs, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/corporatepayment/' . $corporatepayment->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/corporatepayment/' . $corporatepayment->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $corporatepaymentattachment = new Corporatepaymentattachment;
                $corporatepaymentattachment->corporatepayment_id = $corporatepayment->id;
                $corporatepaymentattachment->type = "image";     // add a '/' in the head.
                $corporatepaymentattachment->path = "/$dir$filename";     // add a '/' in the head.
                $corporatepaymentattachment->save();

                array_push($image_urls, $value);
            }
        }

        if (isset($corporatepayment))
        {
//            $inputs['totalprice'] = $corporatepayment->projectsitepurchaseitems->sum('price') + $inputs['freight'];
            $inputs['image_urls'] = json_encode($image_urls);
//            $inputs['approvers'] = $corporatepayment->approvers();
            $response = ApprovalController::corporatepayment($inputs);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $corporatepayment->forceDelete();
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

                $corporatepayment->process_instance_id = $process_instance_id;
                $corporatepayment->business_id = $business_id;
                $corporatepayment->save();

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
        $corporatepayment = Corporatepayment::where('process_instance_id', $processInstanceId)->firstOrFail();
        if (isset($corporatepayment))
        {
            $corporatepayment->status = $status;
            $corporatepayment->save();

            // 如果是审批完成且通过，则创建付款审批单
            if ($status == 0)
            {
                $projectsitepurchase = null;
                $associated_approval_projectpurchases = json_decode($corporatepayment->associated_approval_projectpurchase);
                if (count($associated_approval_projectpurchases) > 0)
                {
                    $associated_approval_projectpurchase = array_first($associated_approval_projectpurchases);
                    if (strlen($associated_approval_projectpurchase) > 0)
                    {
                        $projectsitepurchase = Projectsitepurchase::where('process_instance_id', $associated_approval_projectpurchase)->first();
                    }
                }

                // set approversetting_id
                $approversetting_id = -1;
                $approvaltype_id = PaymentrequestsController::typeid();
                if ($approvaltype_id > 0)
                {
                    $approversettingFirst = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->first();
                    if ($approversettingFirst)
                        $approversetting_id = $approversettingFirst->id;
                    else
                        $approversetting_id = -1;
                }
                else
                    $approversetting_id = -1;

                // 金额
                $amount = $corporatepayment->amount;
                // 注释：直接采用实际输入的金额，2021/2/21
//                if ($corporatepayment->amounttype == '安装合同安装费付款（ERP）')
//                {
//                    $pohead = $corporatepayment->pohead;
//                    if (isset($pohead))
//                        $amount = $pohead->amount * $corporatepayment->amountpercent / 100;
//                }

                // 备注
                $remark = '';
                $client = new DingTalkClient();
                $req = new OapiProcessinstanceGetRequest();
                $req->setProcessInstanceId($processInstanceId);
                $accessToken = DingTalkController::getAccessToken();
                $response = $client->execute($req, $accessToken);
                $response = json_decode(json_encode($response, JSON_UNESCAPED_UNICODE));
                $operation_records = $response->process_instance->operation_records->operation_records_vo;
                $dtuser_whl = Dtuser::where('user_id', 2)->first();
                if (isset($dtuser_whl))
                {
                    foreach ($operation_records as $operation_record)
                    {
                        if ($operation_record->operation_type == 'ADD_REMARK' && $operation_record->userid == $dtuser_whl->userid)
                        {
                            $remark = $operation_record->remark;
                        }
                    }
                }

                $data = [
                    'suppliertype'          => $corporatepayment->suppliertype,
                    'paymenttype'            => $corporatepayment->paymenttype,
                    'supplier_id'            => $corporatepayment->supplier_id,
                    'pohead_id'              => isset($projectsitepurchase->pohead_hxold) ? $projectsitepurchase->pohead_hxold->id : $corporatepayment->pohead_id,
                    'descrip'                => '由工程部发起的付款-对公帐户付款通过后自动创建，对应的审批单号为：' . $corporatepayment->business_id,
                    'amount'                  => $amount,
                    'paymentmethod'         => $corporatepayment->paymentmethod,
                    'datepay'                => $corporatepayment->paydate,
                    'vendbank_id'            => $corporatepayment->vendbank_id,
                    'applicant_id'           => $corporatepayment->applicant_id,
//                                'status'                  => 1,
                    'approversetting_id'    => $approversetting_id,
                    'associated_approval_type'  => 'corporatepayment',
                    'associated_process_instance_id'  => $processInstanceId,
                    'associated_remark'      => $remark,
                ];
                $paymentrequest = Paymentrequest::create($data);

                // auto generate paymentnodeattachments (pdf)
                if ($paymentrequest)
                {
                    $str = '<html>';
                    $str .= '<head>';
                    $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
                    $str .= '</head>';
                    $str .= '<body>';

                    $str .= '<h1 style="font-family: DroidSansFallback; text-align:center">供应商付款节点' . '</h1>';

                    $str .= '<table border="1px" cellpadding="0" cellspacing="0" width="100%"><tbody>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">申请人</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . $paymentrequest->applicant->name . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">申请人部门</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->applicant->dept->name) ? $paymentrequest->applicant->dept->name : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">供应商</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">采购商品名称</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">对应工程名称</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->sohead->descrip) ? $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">项目所属销售经理</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->sohead->salesmanager) ? $paymentrequest->purchaseorder_hxold->sohead->salesmanager : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">工程类型</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->sohead->equipmenttype) ? $paymentrequest->purchaseorder_hxold->sohead->equipmenttype : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">采购合同</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">到货地</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->arrival) ? $paymentrequest->purchaseorder_hxold->arrival : '') . '</td>';
                    $str .= '</tr>';

                    $str .= '<tr>';
                    $str .= '<td style="font-family: DroidSansFallback;">对应的付款-对公帐户付款审批单号</td>';
                    $str .= '<td style="font-family: DroidSansFallback;">' . $corporatepayment->business_id . '</td>';
                    $str .= '</tr>';

                    $str .= '</tbody></table>';

                    $str .= '</body>';
                    $str .= '</html>';

                    // instantiate and use the dompdf class
                    $dompdf = new Dompdf();
                    // $dompdf->set_option('isFontSubsettingEnabled', true);
                    $dompdf->loadHtml($str);

                    // (Optional) Setup the paper size and orientation
                    // $dompdf->setPaper('A4', 'landscape');
                    $dompdf->setPaper('A4');

                    // Render the HTML as PDF
                    $dompdf->render();
                    $destdir = 'uploads/approval/paymentrequest/' . $paymentrequest->id;
                    if (!is_dir($destdir))
                        mkdir($destdir);
                    $dest = $destdir . '/' . date('YmdHis').rand(100, 200) . '.pdf';
                    file_put_contents($dest, $dompdf->output());

                    // Output the generated PDF to Browser
//        $file = $dompdf->stream('供应商到货款节点');
//        dd($dompdf->output());

                    // add database record
                    $paymentnodeattachment = new Paymentrequestattachment;
                    $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                    $paymentnodeattachment->type = "paymentnode";
                    $paymentnodeattachment->filename = '供应商付款节点(自动生成)';
                    $paymentnodeattachment->path = "/$dest";     // add a '/' in the head.
                    $paymentnodeattachment->save();
                }

                if (isset($paymentrequest))
                {
                    // send dingtalk message.
                    $touser = $paymentrequest->nextapprover();
                    if ($touser)
                    {
                        $data = [
                            [
                                'key' => '申请人:',
                                'value' => $paymentrequest->applicant->name,
                            ],
                            [
                                'key' => '付款对象:',
                                'value' => isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '',
                            ],
                            [
                                'key' => '金额:',
                                'value' => $paymentrequest->amount,
                            ],
                            [
                                'key' => '付款类型:',
                                'value' => $paymentrequest->paymenttype,
                            ],
                            [
                                'key' => '对应项目:',
                                'value' => isset($paymentrequest->purchaseorder_hxold->sohead->projectjc) ? $paymentrequest->purchaseorder_hxold->sohead->projectjc : '',
                            ],
                            [
                                'key' => '商品:',
                                'value' => isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '',
                            ],
                        ];

                        $msgcontent_data = [
                            'message_url' => url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'),
                            'pc_message_url' => '',
                            'head' => [
                                'bgcolor' => 'FFBBBBBB',
                                'text' => $paymentrequest->applicant->name . '的供应商付款审批'
                            ],
                            'body' => [
                                'title' => $paymentrequest->applicant->name . '提交的供应商付款审批需要您审批。',
                                'form' => $data
                            ]
                        ];
                        $msgcontent = json_encode($msgcontent_data);

                        $c = new DingTalkClient;
                        $req = new CorpMessageCorpconversationAsyncsendRequest;

                        $access_token = '';
                        if (isset($paymentrequest->purchaseorder_hxold->purchasecompany_id) && $paymentrequest->purchaseorder_hxold->purchasecompany_id == 3)
                        {
                            $access_token = DingTalkController::getAccessToken_appkey('approval');
                            $req->setAgentId(config('custom.dingtalk.hx_henan.apps.approval.agentid'));
//                    $req->setUseridList('04090710367573');
                            $req->setUseridList($touser->dtuserid);
                        }
                        else
                        {
                            $access_token = DingTalkController::getAccessToken();
                            $req->setAgentId(config('custom.dingtalk.agentidlist.approval'));
                            $req->setUseridList($touser->dtuserid);
                        }

                        $req->setMsgtype("oa");
//                $req->setDeptIdList("");
                        $req->setToAllUser("false");
                        $req->setMsgcontent("$msgcontent");
                        $resp = $c->execute($req, $access_token);
                        Log::info(json_encode($resp));
                        if ($resp->code != "0")
                        {
                            Log::info($resp->msg . ": " . $resp->sub_msg);
                        }

                        // send message to applicant
                        $applicant = $corporatepayment->applicant;
                        if (isset($applicant)) {
                            $msg = "你发起的对公账户付款审批单已经审批通过，开始进入付款审批流程。对公付款审批单号：" . $corporatepayment->business_id . "，下一个审批人：" . $touser->name . "。";
                            if (isset($touser)) {
                                $data = [
                                    'userid' => $applicant->id,
                                    'msgcontent' => urlencode($msg),
                                ];

                                $response = DingTalkController::sendCorpMessageTextReminder(json_encode($data));
                            }
                        }
                    }
                }

            }
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $corporatepayment = Corporatepayment::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($corporatepayment)
        {
            $corporatepayment->forceDelete();
        }
    }
}
