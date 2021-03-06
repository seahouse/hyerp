<?php

namespace App\Http\Controllers\Approval;

use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Mcitempurchaseissuedrawing;
use App\Models\Approval\Mcitempurchaseitem;
use App\Models\Approval\Pppayment;
use App\Models\Approval\Pppaymentitem;
use App\Models\Approval\Pppaymentitemissuedrawing;
use App\Models\Approval\Pppaymentitemunitprice;
use App\Models\Product\Itemp_hxold;
use App\Models\Product\Unit_hxold;
use App\Models\Purchase\Vendbank_hxold;
use App\Models\Purchase\Vendinfo_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class SynchronizeController extends Controller
{
    //
    public function index()
    {
        return view('approval.synchronize.index');
    }

    public function synchronize(Request $request)
    {
        $msg = '';

        if (strlen($msg) == 0)
        {
            if (!$request->has('approvaltype'))
                $msg = '未知的审批类型，无法继续。';
        }

        if (strlen($msg) == 0)
        {
            if (!$request->has('business_id'))
                $msg = '未输入business_id';
        }

        if (strlen($msg) == 0)
        {
            $approvaltype = $request->get('approvaltype');
            $business_id = $request->get('business_id');
            $item = null;
            if ($approvaltype == 'issuedrawing')
            {
                $item = Issuedrawing::where('business_id', $business_id)->first();
            }
            elseif ($approvaltype == 'mcitempurchase')
                $item = Mcitempurchase::where('business_id', $business_id)->first();
            elseif ($approvaltype == 'pppayment')
                $item = Pppayment::where('business_id', $business_id)->first();

//            Log::info($item);
            if (isset($item))
                $msg = '此审批单在华星审批中已存在，无法继续同步。';
        }

        if (strlen($msg) == 0)
        {
            $business_id = $request->get('business_id');
//            Log::info(substr($business_id, 0, 12));
            $startTime = Carbon::createFromFormat('YmdHi', substr($business_id, 0, 12));
            $endTime = $startTime->copy()->addMinute();
//            Log::info($startTime);
//            Log::info($endTime);
            $approvaltype = $request->get('approvaltype');
            $response = ApprovalController::processinstance_listids($approvaltype, $startTime, $endTime);
//            Log::info(json_encode($response));
            if ($response->result->ding_open_errcode == "0")
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
                        if ($approvaltype == 'issuedrawing')
                        {
                            //                                Log::info(json_encode($formData));
                            $input = [];
//                                Log::info($formData['设计部门']);
                            $input['designdepartment'] = $formData['设计部门'];

                            $sohead = Salesorder_hxold::where('number', $formData['项目编号'])->first();
                            if (isset($sohead))
                                $input['sohead_id'] = $sohead->id;
                            else
                                $msg = '销售订单不存在，无法继续。';
                            $input['overview'] = $formData['制作概述'];
                            $input['tonnage'] = $formData['吨位（吨）'];
                            $input['productioncompany'] = $formData['制作公司'];
                            $input['materialsupplier'] = $formData['材料供应方'];
                            $drawingchecker = User::where('name', $formData['图纸校核人'])->first();
                            if (isset($drawingchecker))
                                $input['drawingchecker_id'] = $drawingchecker->id;
                            else
                                $msg = '图纸校核人不存在，无法继续。';
                            $input['requestdeliverydate'] = $formData['要求发货日'];
                            $input['drawingcount'] = $formData['图纸份数（份）'];
                            $input['remark'] = $formData['备注'];
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
                            }
                            else
                                $msg = '此审批单还未结束，无法继续';
                            $input['process_instance_id'] = "$item->process_instance_id";
                            $input['business_id'] = "$item->business_id";

//                                Log::info(json_encode($input));

                            if (strlen($msg) == 0)
                            {
                                $issuedrawing = Issuedrawing::create($input);
                                if (isset($issuedrawing))
                                    $msg = '同步成功。';
                            }
                        }
                        elseif ($approvaltype == 'mcitempurchase')
                        {
                            //                                Log::info(json_encode($formData));
                            $input = [];
//                                Log::info($formData['设计部门']);
                            $input['manufacturingcenter'] = $formData['所属制造中心'];
                            $input['itemtype'] = $formData['申购物品类型'];
                            $input['expirationdate'] = $formData['要求最晚到货时间'];

                            $sohead = Salesorder_hxold::where('number', $formData['项目编号'])->first();
                            if (isset($sohead))
                                $input['sohead_id'] = $sohead->id;
                            else
                                $msg = '销售订单不存在，无法继续。';
                            $input['totalprice'] = $formData['总价（元）'];
                            $input['detailuse'] = $formData['采购物品详细用途'];
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
                            }
                            else
                                $msg = '此审批单还未结束，无法继续';
                            $input['process_instance_id'] = "$item->process_instance_id";
                            $input['business_id'] = "$item->business_id";

//                            Log::info(json_encode($input));
//                            $issuedrawing_numbers = explode(',', $formData['下发图纸审批单号']);



                            if (strlen($msg) == 0)
                            {
                                $mcitempurchase = Mcitempurchase::create($input);
                                if (isset($mcitempurchase))
                                {
                                    $detail_items = json_decode($formData['明细']);
                                    foreach ($detail_items as $detail_item)
                                    {
                                        $formData_item = [];
                                        foreach ($detail_item as $item_value)
                                        {
//                                            Log::info($item_value->label . ": " . $item_value->value);
                                            $formData_item["$item_value->label"] = "$item_value->value";
                                        }

                                        $input_item = [];
                                        $input_item['mcitempurchase_id'] = $mcitempurchase->id;
                                        $itemp = Itemp_hxold::where('goods_name', $formData_item['物品名称'])->where('goods_spec', $formData_item['规格型号'])->first();
                                        if (isset($itemp))
                                            $input_item['item_id'] = $itemp->goods_id;
                                        $input_item['size'] = $formData_item['尺寸'];
                                        $input_item['material'] = $formData_item['材质'];
                                        $input_item['unitprice'] = $formData_item['单价（可不填）'];
                                        $unit = Unit_hxold::where('name', $formData_item['单位'])->first();
                                        if (isset($unit))
                                            $input_item['unit_id'] = $unit->id;
                                        $input_item['quantity'] = $formData_item['数量'];
                                        $input_item['weight'] = $formData_item['重量（吨）'];
                                        $input_item['remark'] = $formData_item['备注'];
                                        $input_item['seq'] = 0;

                                        if ($input_item['item_id'] > 0)
                                            Mcitempurchaseitem::create($input_item);
//                                        Log::info(json_encode($input_item));
                                    }

                                    foreach (explode(",", $formData['下发图纸审批单号']) as $issuedrawing_business_id) {
//                                        Log::info($issuedrawing_business_id);
                                        $issuedrawing = Issuedrawing::where('business_id', $issuedrawing_business_id)->first();
                                        if (isset($issuedrawing))
                                        {
                                            Mcitempurchaseissuedrawing::create(array('mcitempurchase_id' => $mcitempurchase->id, 'issuedrawing_id' => $issuedrawing->id));
                                        }
                                    }
                                    $msg = '同步成功。';
                                }
                            }
                        }
                        elseif ($approvaltype == 'pppayment')
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
                            }
                            else
                                $msg = '此审批单还未结束，无法继续';
                            $input['process_instance_id'] = "$item->process_instance_id";
                            $input['business_id'] = "$item->business_id";

//                            Log::info(json_encode($input));

                            if (strlen($msg) == 0)
                            {
                                $pppayment = Pppayment::create($input);
                                if (isset($pppayment))
                                {
                                    $detail_items = json_decode($formData['加工明细']);
                                    foreach ($detail_items as $detail_item)
                                    {
                                        $formData_item = [];
                                        foreach ($detail_item as $item_value)
                                        {
//                                            Log::info($item_value->label . ": " . $item_value->value);
                                            $formData_item["$item_value->label"] = "$item_value->value";
                                        }

                                        $input_item = [];
                                        $input_item['pppayment_id'] = $pppayment->id;
                                        $sohead = Salesorder_hxold::where('number', $formData_item['所属项目编号'])->first();
                                        if (isset($sohead))
                                            $input_item['sohead_id'] = $sohead->id;
                                        else
                                            $input_item['sohead_id'] = 0;
                                        $input_item['productionoverview'] = $formData_item['制作概述'];
                                        $input_item['tonnage'] = $formData_item['吨位'];
                                        $input_item['area'] = $formData_item['地区'];
                                        $input_item['type'] = $formData_item['类型'];
                                        $input_item['seq'] = 0;

                                        if ($input_item['sohead_id'] > 0)
                                        {
                                            $pppaymentitem = Pppaymentitem::create($input_item);

                                            if (isset($pppaymentitem))
                                            {
                                                foreach (explode(",", $formData_item['图纸下发单号']) as $issuedrawing_business_id) {
//                                                    Log::info($issuedrawing_business_id);
                                                    if (strlen(trim($issuedrawing_business_id)) > 0)
                                                    {
                                                        $issuedrawing = Issuedrawing::where('business_id', $issuedrawing_business_id)->first();
                                                        if (isset($issuedrawing))
                                                        {
                                                            Pppaymentitemissuedrawing::create(array('pppaymentitem_id' => $pppaymentitem->id, 'issuedrawing_id' => $issuedrawing->id));
                                                        }
                                                    }
                                                }

                                                $detail_item_unitprices = explode("\n", $formData_item['单价明细']);
                                                foreach ($detail_item_unitprices as $detail_item_unitprice)
                                                {
//                                                    Log::info($detail_item_unitprice);
                                                    $detail_item_unitprice_name = substr($detail_item_unitprice, 0, strpos($detail_item_unitprice, ":"));
                                                    $detail_item_unitprice_value = substr($detail_item_unitprice, strpos($detail_item_unitprice, ":") + 1);
                                                    $detail_item_unitprice_unitprice = substr($detail_item_unitprice_value, strpos($detail_item_unitprice_value, "*") + 1, strpos($detail_item_unitprice_value, "元") - strpos($detail_item_unitprice_value, "*") - 1);
                                                    $detail_item_unitprice_tonnage = substr($detail_item_unitprice_value, 0, strpos($detail_item_unitprice_value, "吨"));
//                                                    Log::info($detail_item_unitprice_name . "\t" . $detail_item_unitprice_unitprice . "\t" . $detail_item_unitprice_tonnage);
                                                    Pppaymentitemunitprice::create(array(
                                                        'pppaymentitem_id'      => $pppaymentitem->id,
                                                        'name'                    => $detail_item_unitprice_name,
                                                        'unitprice'              => $detail_item_unitprice_unitprice,
                                                        'tonnage'                => $detail_item_unitprice_tonnage,
                                                    ));
                                                }
                                            }


                                        }
//                                        Log::info(json_encode($input_item));
                                    }


                                    $msg = '同步成功。';
                                }
                            }
                        }

                        break;
                    }
                    else
                        continue;
//                    Log::info(json_encode($item));
                }
            }
            else
                $msg = '获取钉钉审批单失败。';
//            Log::info($response->result->ding_open_errcode);
        }

        return $msg;
    }
}
