<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Models\Approval\Projectsitepurchase;
use App\Models\Approval\Projectsitepurchaseattachment;
use App\Models\Approval\Projectsitepurchaseitem;
use App\Models\Basic\Company_hxold;
use App\Models\Product\Itemp_hxold;
use App\Models\Product\Unit_hxold;
use App\Models\Purchase\Poitem_hx;
use App\Models\Purchase\Purchaseorder_hx;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Userold;
use Carbon\Carbon;
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
        self::updateStatusByProcessInstanceId('0f390e2b-7be7-4645-96d6-4f7efb9ecf70', 0);
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
        $client = new DingTalkClient();
        $req = new OapiProcessinstanceCspaceInfoRequest();
        $req->setUserId(Auth::user()->dtuserid);
        $response = $client->execute($req, $config['session']);
//        dd(json_decode(json_encode($response))->result->space_id);
        $config['spaceid'] = json_decode(json_encode($response))->result->space_id;
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


        if ($input['totalprice'] == "")
            $input['totalprice'] = 0.0;
        $input['applicant_id'] = Auth::user()->id;

        $input['purchasecompany_name'] = '';
        if (array_key_exists('purchasecompany_id', $input) && $input['purchasecompany_id'] > 0)
        {
            $purchasecompany = Company_hxold::find($input['purchasecompany_id']);
            if (isset($purchasecompany))
                $input['purchasecompany_name'] = $purchasecompany->name;
        }

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

//        dd($input);
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
            $input['associatedapprovals'] = strlen($input['associatedapprovals']) > 0 ? json_encode(array($input['associatedapprovals'])) : "";
//            dd($input['associatedapprovals']);
            $input['approvers'] = $projectsitepurchase->approvers();
            $response = ApprovalController::projectsitepurchase($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $projectsitepurchase->forceDelete();
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

            // 如果是审批完成且通过，则创建老系统中的采购订单
            if ($status == 0)
            {
                $cp = 'WX';
                if ($projectsitepurchase->purchasecompany_id == 2)
                    $cp = 'AH';
                elseif ($projectsitepurchase->purchasecompany_id == 3)
                    $cp = 'HN';

                $projectsitepurchaseitem = $projectsitepurchase->projectsitepurchaseitems->first();
                $item_index = '';
                if (isset($projectsitepurchaseitem))
                {
                    $item_index = HelperController::pinyin_long($projectsitepurchaseitem->item->goods_name);
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
                $userold = Userold::where('user_id', $projectsitepurchase->applicant_id)->first();
                if (isset($userold))
                    $userold_id = $userold->user_hxold_id;

                $pohead_number = $cp . '-' . $item_index . '-' . Carbon::today()->format('Y-m') . '-' . $seqnumber;

                $techpurchaseattachment_techspecification = $projectsitepurchase->projectsitepurchaseattachments->where('type', 'image')->first();

                $sohead_name = '';
                $sohead = Salesorder_hxold::find($projectsitepurchase->sohead_id);
                if (isset($sohead))
                    $sohead_name = $sohead->number . "|" . $sohead->custinfo_name . "|" . $sohead->descrip . "|" . $sohead->amount;

                $data = [
                    'purchasecompany_id'    => $projectsitepurchase->purchasecompany_id,
                    '采购订单编号'            => $pohead_number,
                    '申请人ID'                => $userold_id,
                    '对应项目ID'              => $projectsitepurchase->sohead_id,
                    '项目名称'                => $sohead_name,
                    '申请到位日期'            => $projectsitepurchase->arrivaldate,
                    '修造或工程'             => $cp,
                    '技术规范书'             => isset($techpurchaseattachment_techspecification) ? $techpurchaseattachment_techspecification->filename : '',
                    '编号年份'                => Carbon::today()->year,
                    '编号数字'                => $seqnumber,
                    '编号商品名称'            => $item_index,
                    '采购订单状态'            => 10,
                ];
//                dd($data);
                $pohead = Purchaseorder_hx::create($data);

                if (isset($pohead))
                {
                    foreach ($projectsitepurchase->projectsitepurchaseitems as $projectsitepurchaseitem)
                    {
                        $item = Itemp_hxold::where('goods_id', $projectsitepurchaseitem->item_id)->first();
                        if (isset($item))
                        {
                            $unit_name = '';
                            $unit = Unit_hxold::find($projectsitepurchaseitem->unit_id);
                            if (isset($unit))
                                $unit_name = $unit->name;
                            $data = [
                                'order_id'      => $pohead->id,
                                'goods_id'      => $projectsitepurchaseitem->item_id,
                                'goods_name'    => $item->goods_name,
                                'goods_number'  => $projectsitepurchaseitem->quantity,
                                'goods_unit'    => $unit_name,
                            ];
                            Poitem_hx::create($data);
                        }
                    }

                    // 拷贝“技术规范书”到对应的ERP目录下
                    if (isset($techpurchaseattachment_techspecification))
                    {
                        $dir = config('custom.hxold.purchase_techspecification_dir') . $pohead->id . "/";
                        if (!is_dir($dir)) {
                            mkdir($dir);
                        }
                        copy(public_path($techpurchaseattachment_techspecification->path), $dir . $techpurchaseattachment_techspecification->filename);
                    }
                }
            }
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
