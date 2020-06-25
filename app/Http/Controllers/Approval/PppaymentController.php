<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Pppayment;
use App\Models\Approval\Pppaymentitem;
use App\Models\Approval\Pppaymentitemattachment;
use App\Models\Approval\Pppaymentitemissuedrawing;
use App\Models\Approval\Pppaymentitemunitprice;
use App\Models\Purchase\Vendbank_hxold;
use App\Models\Purchase\Vendinfo_hxold;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Log, Storage;

class PppaymentController extends Controller
{
    private static $approvaltype_name = "生产加工单结算付款";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
        $inputs = $request->all();
        $pppayments = $this->searchrequest($request);

        return view('approval.pppayments.index', compact('pppayments', 'inputs'));
    }

    public function searchrequest($request)
    {
        $key = $request->input('key');


        $query = Pppayment::latest('created_at');

        if (strlen($key) > 0)
        {
            $query->where('business_id', 'like', '%'.$key.'%');
        }



        $pppayments = $query->select('pppayments.*')
            ->paginate(10);

        // $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        // dd($purchaseorders->pluck('id'));

        return $pppayments;
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

        return view('approval/pppayments/mcreate', compact('config'));
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


//        $input = array(
//            '_token' => 'MXvSgAhoJ7JkDQ1f5zJvjbtMzdfZ4pePk9xE74Ud', 'manufacturingcenter' => '无锡制造中心机械车间', 'itemtype' => '消耗品类－如焊条', 'expirationdate' => '2018-04-16',
//            'project_name' => '厂部管理费用', 'sohead_id' => '7550', 'sohead_number' => 'JS-GC-00E-2016-04-0025', 'issuedrawing_numbers' => '', 'issuedrawing_values' => '', 'item_name' => '保温条',
//            'item_id' => '14818', 'item_spec' => 'φ32', 'unit' => 'm', 'unitprice' => '', 'quantity' => '12', 'weight' => '',
//            'items_string' => '[{"item_id":"14806","item_name":"PPR管","item_spec":"φ32","unit":"根","unitprice":0,"quantity":"3","weight":0},{"item_id":"14807","item_name":"PPR内丝直接","item_spec":"φ32 DN15","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14808","item_name":"PPR内丝直接","item_spec":"φ32 DN25","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14809","item_name":"PPR直接","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14810","item_name":"PPR大小头","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14811","item_name":"PPR球阀","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14812","item_name":"PPR弯头","item_spec":"","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14813","item_name":"PPR三通","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14814","item_name":"PPR三通","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14817","item_name":"PPR内丝直接","item_spec":"φ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14816","item_name":"管卡","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"20","weight":0},{"item_id":"14818","item_name":"保温条","item_spec":"φ32","unit":"m","unitprice":0,"quantity":"12","weight":0}]',
////            'items_string' => '[{"item_id":"14806","item_name":"PPR管","item_spec":"φ32","unit":"根","unitprice":0,"quantity":"3","weight":0},{"item_id":"14807","item_name":"PPR内丝直接","item_spec":"φ32 DN15","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14808","item_name":"PPR内丝直接","item_spec":"φ32 DN25","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14809","item_name":"PPR直接","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14810","item_name":"PPR大小头","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14811","item_name":"PPR球阀","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14812","item_name":"PPR弯头","item_spec":"","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14813","item_name":"PPR三通","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14814","item_name":"PPR三通","item_spec":"φ32xφ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14817","item_name":"PPR内丝直接","item_spec":"φ22","unit":"只","unitprice":0,"quantity":"5","weight":0},{"item_id":"14816","item_name":"管卡","item_spec":"φ32","unit":"只","unitprice":0,"quantity":"20","weight":0},{"item_id":"14818","item_name":"保温条","item_spec":"φ32","unit":"m","unitprice":0,"quantity":"12","weight":0}]',
//            'totalprice' => '0', 'detailuse' => '上述材料问雾化器研发中心用', 'applicant_id' => '38', 'approversetting_id' => '-1', 'images' => array(null),
//            'approvers' => 'manager1200');

        $this->validate($request, [
            'productioncompany'         => 'required',
            'designdepartment'          => 'required',
            'paymentreason'              => 'required',
            'items_string'               => 'required',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
            'paymentdate'             => 'required',
            'supplier_id'             => 'required',
        ]);
//        $input = HelperController::skipEmptyValue($input);


        // valid
        $totaltonnage = 0.0;
        $pppayment_items = json_decode($input['items_string']);
        foreach ($pppayment_items as $value) {
            if ($value->sohead_id > 0)
            {
                $totaltonnage += $value->tonnage;
            }
        }
        $input['totaltonnage'] = $totaltonnage;
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
//            if ($totaltonnage > $weight_issuedrawing * 1.3)
//                dd('申购重量超过了图纸重量');
//            $weight_sohead_issuedrawing = 0.0;
//            $weight_sohead_purchase = 0.0;
//            $issuedrawings = Issuedrawing::where('sohead_id', $input['sohead_id'])->get();
//            foreach ($issuedrawings as $issuedrawing)
//            {
//                $weight_sohead_issuedrawing += $issuedrawing->tonnage;
//            }
//            $mcitempurchases = Mcitempurchase::where('sohead_id', $input['sohead_id'])->where('status', '>=', 0)->get();
//            foreach ($mcitempurchases as $mcitempurchase)
//            {
//                $weight_sohead_purchase += $mcitempurchase->mcitempurchaseitems->sum('tonnage');
//            }
//            if (($weight_sohead_purchase + $totaltonnage)  > $weight_sohead_issuedrawing * 1.2)
//                dd('该订单的申购重量之和超过了图纸重量之和');
//        }

        if ($input['totalpaid'] == "")
            $input['totalpaid'] = 0.0;
        if ($input['amount'] == "")
            $input['amount'] = 0.0;
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

        $pppayment = Pppayment::create($input);
//        dd($mcitempurchase);

        // create mcitempurchaseitems
        $pppayment_items = json_decode($input['items_string']);
        $totaltotalprice = 0.0;
        foreach ($pppayment_items as $pppayment_item) {
            if ($pppayment_item->sohead_id > 0)
            {
                $item_array = json_decode(json_encode($pppayment_item), true);
                $item_array['pppayment_id'] = $pppayment->id;
                $pppaymentitem = Pppaymentitem::create($item_array);

                // create issuedrawings
                if (isset($pppaymentitem))
                {
                    $issuedrawing_values = $pppayment_item->issuedrawing_values;
                    foreach (explode(",", $issuedrawing_values) as $value) {
                        if ($value > 0)
                        {
                            Pppaymentitemissuedrawing::create(array('pppaymentitem_id' => $pppaymentitem->id, 'issuedrawing_id' => $value));
                        }
                    }


                    $image_urls = [];
                    // create images in the desktop
                    if ($pppaymentitem && isset($pppayment_item->imagesname))
                    {
                        $files = array_get($input, $pppayment_item->imagesname);
//                        $files = array_get($input,'images');
                        $destinationPath = 'uploads/approval/pppayment/' . $pppayment->id . '/images/';
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
                                    $pppaymentitemeattachment = new Pppaymentitemattachment();
                                    $pppaymentitemeattachment->pppaymentitem_id = $pppaymentitem->id;
                                    $pppaymentitemeattachment->type = "image";
                                    $pppaymentitemeattachment->filename = $originalName;
                                    $pppaymentitemeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                                    $pppaymentitemeattachment->save();

                                    array_push($image_urls, url($destinationPath . $filename));
                                }
                            }
                        }
                    }

                    // create images from dingtalk mobile
                    if ($pppaymentitem && isset($pppayment_item->imagesname_mobile))
                    {
                        $imagesname_mobile = $pppayment_item->imagesname_mobile;

//                        $images = array_where($input, function($key, $value) {
//                            if (substr_compare($key, 'image_', 0, 6) == 0)
//                                return $value;
//                        });

                        $destinationPath = 'uploads/approval/pppayment/' . $pppayment->id . '/images/';
                        foreach (explode(",", $imagesname_mobile) as $imagesname_mobile_item) {
                            # code...
                            if (strlen(trim($imagesname_mobile_item)) == 0) continue;

                            // save image file.
                            $sExtension = substr($imagesname_mobile_item, strrpos($imagesname_mobile_item, '.') + 1);
                            $dir = 'images/approval/pppayment/' . $pppayment->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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

                            Storage::put($destinationPath . $filename, file_get_contents($imagesname_mobile_item));

                            file_put_contents("$dir/$filename", file_get_contents($imagesname_mobile_item));


                            // add image record
                            $pppaymentitemattachment = new Pppaymentitemattachment;
                            $pppaymentitemattachment->pppaymentitem_id = $pppaymentitem->id;
                            $pppaymentitemattachment->type = "image";     // add a '/' in the head.
                            $pppaymentitemattachment->path = "/$dir$filename";     // add a '/' in the head.
                            $pppaymentitemattachment->save();

                            array_push($image_urls, $imagesname_mobile_item);
                        }
                    }

                    $input[$pppayment_item->imagesname] = json_encode($image_urls);

                    // create pppaymentitem unitprices
                    $strunitprices = $pppayment_item->unitprice_array;
                    $dtunitpricedetail = [];
                    $totalprice = 0.0;
                    foreach ($strunitprices as $unitprice_item) {
                        $unitprice_array = json_decode(json_encode($unitprice_item), true);

                        // 当吨位大于0的时候才有效，2020/6/24
                        if ($unitprice_array['tonnage'] > 0.0)
                        {
                            $unitprice_array['pppaymentitem_id'] = $pppaymentitem->id;
                            $pppaymentitemunitprice = Pppaymentitemunitprice::create($unitprice_array);

                            if (isset($pppaymentitemunitprice))
                            {
                                $price = $pppaymentitemunitprice->unitprice * $pppaymentitemunitprice->tonnage;
                                $totalprice += $price;
                                array_push($dtunitpricedetail, $pppaymentitemunitprice->name . ':' . $pppaymentitemunitprice->tonnage . '吨*' . $pppaymentitemunitprice->unitprice . '元=' . $price . '元');
                            }
                        }
                    }
                    $totaltotalprice += $totalprice;
                    $input[$pppayment_item->unitprice_inputname] = implode("\n", $dtunitpricedetail);
                    $input[$pppayment_item->totalprice_inputname] = $totalprice;
                }
            }
        }
        $input['amount'] = $totaltotalprice;

        $pppayment->amount = $totaltotalprice;
        $pppayment->save();



        if (isset($pppayment))
        {
            $input['image_urls'] = json_encode($image_urls);
            $input['approvers'] = $pppayment->approvers();
//            Log::info('amount2:' . $input['amount']);
            $response = ApprovalController::pppayment($input);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->result->ding_open_errcode <> 0)
            {
                $pppayment->forceDelete();
                Log::info(json_encode($input));
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

                $pppayment->process_instance_id = $process_instance_id;
                $pppayment->business_id = $business_id;
                $pppayment->save();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $pppayment = Pppayment::findOrFail($id);
        return view('approval.pppayments.show', compact('pppayment'));
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
        $pppayment = Pppayment::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($pppayment)
        {
            $pppayment->status = $status;
            $pppayment->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $pppayment = Pppayment::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($pppayment)
        {
            $pppayment->forceDelete();
        }
    }

    public function synchronize_status_to_erp(Request $request)
    {
        $query = Pppayment::latest('created_at');
        if ($request->has('createdatestart') && $request->has('createdateend'))
        {
            $query->whereRaw("DATEDIFF(DAY, created_at, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, created_at, '" . $request->input('createdateend') . "') >=0");
        }

        $pppayments = $query->select('*')->get();
        $count = 0;
        Log::info($pppayments->count());
        foreach ($pppayments as $pppayment)
        {
            $business_id = $pppayment->business_id;
            if (empty($business_id)) continue;
//            Log::info(substr($business_id, 0, 12));
            $startTime = Carbon::createFromFormat('YmdHi', substr($business_id, 0, 12));
            $endTime = $startTime->copy()->addMinute();
            $approvaltype = 'pppayment';
            $response = ApprovalController::processinstance_listids($approvaltype, $startTime, $endTime);
            Log::info('response: ' . json_encode($response));
            if ($response->result->ding_open_errcode == "0")
            {
                if (isset($response->result->result->list))
                {
                    foreach ($response->result->result->list->process_instance_top_vo as $item)
                    {
                        if ($item->business_id == $business_id)
                        {
//                        $approvaltype = $request->get('approvaltype');
                            $formData = [];
                            $user = User::where('dtuserid', $item->originator_userid)->first();
                            foreach ($item->form_component_values->form_component_value_vo as $formvalue)
                            {
//                            Log::info(json_encode($formvalue));
//                            Log::info($formvalue->name . ": " . $formvalue->value);
                                $formData["$formvalue->name"] = "$formvalue->value";
                            }
                            if ($approvaltype == 'pppayment')
                            {
                                $input = [];
                                $input['productioncompany'] = $formData['制作公司'];
                                $input['designdepartment'] = $formData['设计部门'];
                                $input['paymentreason'] = $formData['付款事由'];
                                $input['invoicingsituation'] = $formData['发票开具情况'];
                                $input['totalpaid'] = $formData['该加工单已付款总额'];
                                $input['amount'] = $formData['本次申请付款总额'];
                                $input['paymentdate'] = $formData['支付日期'];
                                $supplier = Vendinfo_hxold::where('name', $formData['支付对象'])->first();
                                if (isset($supplier))
                                    $input['supplier_id'] = $supplier->id;
                                else
                                    $input['supplier_id'] = 0;
                                $vendbank = Vendbank_hxold::where('bankname', $formData['开户行'])->where('accountnum', $formData['开户行'])->first();
                                if (isset($vendbank))
                                    $input['vendbank_id'] = $vendbank->id;
                                else
                                    $input['vendbank_id'] = 0;

                                if (isset($user))
                                {
                                    $input['applicant_id'] = $user->id;
                                }
                                else
                                    $msg = '发起人不存在，无法继续。';
                                $input['approversetting_id'] = -1;
                                if ($item->status == "COMPLETED")
                                {
                                    if ($item->process_instance_result == "agree")
                                        $input['status'] = 0;
                                    else
                                        $input['status'] = -1;

                                    if ($input['status'] != $pppayment->status)
                                    {
                                        $pppayment->status = $input['status'];
                                        $pppayment->save();
                                        $count++;
                                    }
                                }
                                else
                                    $msg = '此审批单还未结束，无法继续';
                                $input['process_instance_id'] = "$item->process_instance_id";
                                $input['business_id'] = "$item->business_id";

//                            Log::info(json_encode($input));

                            }

                            break;
                        }
                        else
                            continue;
//                    Log::info(json_encode($item));
                    }
                }
            }
            else
                $msg = '获取钉钉审批单失败。';
        }

        $data = [
            'errcode' => 0,
            'errmsg' => '同步成功，共修改了' . $count . '个审批单。',
        ];
        return response()->json($data);
    }

    public function getpricedetailhtml(Request $request)
    {
        $strhtml = "";
//        Log::info($request->all());
        if ($request->has('selecttype') && $request->has('productioncompany') && $request->has('selectarea'))
        {
            foreach (config('custom.dingtalk.approversettings.pppayment.pricedetail.' . $request->input('selecttype')) as $key => $value)
            {
                $strhtml .= "<div class=\"form-group\" name=\"div_unitpriceitem\">";
                $strhtml .= '<label for="paowan" class="col-xs-4 col-sm-2 control-label">' . $key . ':</label>
                            <div class="col-sm-5 col-xs-4">
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="' . $key . '">
                            </div>
                            <div class="col-sm-5 col-xs-4">';
                $strhtml .='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' . $value[$request->input('productioncompany')][$request->input('selectarea')] . '" readonly="readonly">';
                $strhtml .= '</div>';
                $strhtml .= '</div>';
            }
        }

//        $data = [
//            'productioncompany' => '泰州分公司',
//            'selecttype'         => '国外',
//        ];
//        Log::info($strhtml);
        return $strhtml;
//        return response()->json($data);
    }
}
