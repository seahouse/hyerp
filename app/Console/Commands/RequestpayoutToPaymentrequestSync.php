<?php

namespace App\Console\Commands;

use App\Http\Controllers\Approval\ApprovalController;
use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceGetRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceListidsRequest;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class RequestpayoutToPaymentrequestSync extends Command
{
    /**
     * 由于重新设计，由华星审批发起，同步到钉钉的方式。这个命令的方式不再使用，2020/5/2
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:requestpayouttopaymentrequest {days=2} {--type=工程现场采购费用相关}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '请款到付款审批同步：将钉钉的员工请款通用审批单中的《工程采购》借款同步到ERP中的付款审批单.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $client = new DingTalkClient();
        $request = new OapiProcessinstanceListidsRequest();
        $session = DingTalkController::getAccessToken();

        $statDate = Carbon::today()->subDays($this->argument('days'));
        $endDate = Carbon::now();
        if ($statDate->diffInDays($endDate) >= 179)
            $endDate = $statDate->copy()->addDays(179);             // do not exceed 180 days.

        $this->info($statDate->toDateTimeString());
        $this->info($endDate->toDateTimeString());
        $startTime = $statDate->timestamp * 1000;
        $endTime = $endDate->timestamp * 1000;
        $this->info($startTime);
        $this->info($endTime);
        $processcode = config('custom.dingtalk.approval_processcode.corporatepayment');
        $request->setProcessCode($processcode);
        $request->setStartTime("$startTime");
        $request->setEndTime("$endTime");
//        $request->setTemplateName($this->option('template'));
        $request->setSize("10");

        $type = $this->option('type');
        $cursor = 0;
        while (true)
        {
            $request->setCursor("$cursor");
            $response = $client->execute($request, $session);
            Log::info(json_encode($response));
            $this->info(json_encode($response));

            if ($response->errcode == "0")
            {
                if (isset($response->result->list->string))
                {
                    foreach ($response->result->list->string as $process_instance_id)
                    {
                        $this->info($process_instance_id);
                        if (strlen($process_instance_id) > 0)
                        {
                            $requestdetail = new OapiProcessinstanceGetRequest();
                            $requestdetail->setProcessInstanceId("$process_instance_id");
                            $responsedetail = $client->execute($requestdetail, $session);
                            Log::info(json_encode($responsedetail));
                            $this->info(json_encode($responsedetail));

                            if ($responsedetail->errcode == "0")
                            {
                                if (isset($responsedetail->process_instance->form_component_values->form_component_value_vo))
                                {
                                    $form_component_value_vos = $responsedetail->process_instance->form_component_values->form_component_value_vo;
                                    $amounttype = "";
                                    foreach ($form_component_value_vos as $form_component_value_vo)
                                    {
                                        Log::info($form_component_value_vo->component_type);
                                        Log::info($form_component_value_vo->ext_value);
                                        Log::info($form_component_value_vo->id);
                                        Log::info($form_component_value_vo->name);
                                        Log::info($form_component_value_vo->value);

                                        if ($form_component_value_vo->name == "费用类型")
                                        {
                                            $amounttype = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "付款说明")
                                        {
                                            $descrip = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "付款总额")
                                        {
                                            $amount = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "付款方式")
                                        {
                                            $paymentmethod = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "支付日期")
                                        {
                                            $datepay = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "支付单位全称")
                                        {
                                            $supplier_name = $form_component_value_vo->value;
                                        }
                                        if ($form_component_value_vo->name == "关联工程采购审批单")
                                        {
                                            // {"list":[{"businessId":"202004042329000331191","procInstId":"0f390e2b-7be7-4645-96d6-4f7efb9ecf70"}]}
//                                            Log::info($form_component_value_vo->ext_value);
                                            $listObject = json_decode($form_component_value_vo->ext_value);
//                                            Log::info($listObject->list);
                                            if (isset($listObject->list))
                                            {
                                                $listArray = json_decode(json_encode($listObject->list));
                                                foreach ($listArray as $item)
                                                {
                                                    $businessId = $item->businessId;
                                                    Log::info($businessId);
                                                }
                                            }
                                        }
                                    }
                                    if ($amounttype == $type)
                                    {
                                        Log::info('aaa');
                                    }

                                    return;
                                }
                            }
                        }


                    }
                }
            }
            else
            {
                Log::info("获取钉钉日志失败: " . $response->result->error_msg);
                break;
            }

            if (isset($response->result->result->has_more))
            {
                if ($response->result->result->has_more == "false")
                    break;
                else
                {
                    $cursor = $response->result->result->next_cursor;
//                Log::info("cursor:" . $cursor);
                }
            }
            else
                break;

        }
        return;


//        $startTime = Carbon::createFromFormat('YmdHi', substr($business_id, 0, 12));
        $statDate = Carbon::today()->subDays($this->argument('days'));
        $endDate = Carbon::now();
        if ($statDate->diffInDays($endDate) >= 179)
            $endDate = $statDate->copy()->addDays(179);             // do not exceed 180 days.
        $this->info($statDate);
        $this->info($endDate);
//        $startTime = $statDate->timestamp * 1000;
//        $endTime = $endDate->timestamp * 1000;
//        $this->info($startTime);
//        $this->info($endTime);
        $approvaltype = 'requestpayout';
        $response = ApprovalController::processinstance_listids($approvaltype, $statDate, $endDate);
        Log::info(json_encode($response));
        if ($response->result->ding_open_errcode == "0")
        {
            foreach ($response->result->result->list->process_instance_top_vo as $item)
            {
                $this->info($item->business_id);
                $formData = [];
                $user = User::where('dtuserid', $item->originator_userid)->first();
                foreach ($item->form_component_values->form_component_value_vo as $formvalue)
                {
//                            Log::info(json_encode($formvalue));
//                            Log::info($formvalue->name . ": " . $formvalue->value);
                    $formData["$formvalue->name"] = "$formvalue->value";
                }
                Log::info($formData);
                return;
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
        }
        else
            $msg = '获取钉钉审批单失败。';
    }
}
