<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\util\HttpDingtalkEco;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\domain\FormComponentValueVo;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\SmartworkBpmsProcessinstanceCreateRequest;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Paymentrequestretract;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Approval\Reimbursement;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestapproval;
use Auth, DB, Log;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\DingTalkController;

class ApprovalController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmy()
    {
        //
        // $userid = Auth::user()->id;

        // $page = Input::get('page', 1);
        // $paginate = 50;


        // $reimbursements = DB::table('reimbursements')
        //     ->leftJoin('users', 'users.id', '=', 'reimbursements.applicant_id')
        //     ->select('reimbursements.id', 'users.name as applicant_name', 'reimbursements.approversetting_id as status', DB::raw('\'报销\' as type'), 'reimbursements.created_at', DB::raw('\'/approval/reimbursements/mshow/\' as url'))
        //     ->where('applicant_id', $userid);
        // $paymentrequests = DB::table('paymentrequests')
        //     ->leftJoin('users', 'users.id', '=', 'paymentrequests.applicant_id')
        //     ->select('paymentrequests.id', 'users.name as applicant_name', 'paymentrequests.status', DB::raw('\'付款\' as type'), 'paymentrequests.created_at', DB::raw('\'/approval/paymentrequests/mshow/\' as url'))
        //     ->where('applicant_id', $userid)
        //     ->union($reimbursements)
        //     ->latest('created_at')
        //     // ->take(10)
        //     ->get();

        // $offSet = ($page * $paginate) - $paginate;
        // $itemsForCurrentPage = array_slice($paymentrequests, $offSet, $paginate, true);
        // $items = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($paymentrequests), $paginate, $page);


        // $dtuser = Auth::user()->dtuser;
        // // $dduser = Auth::user()->dingtalkGetUser();

        // return view('approval.mindexmy', compact('items', 'dtuser'));

        // $request = request();
        // if ($request->has('key'))
        //     $paymentrequests = $this->search2($request->input('key'));
        // else
        //     $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);

        // if ($request->has('key'))
        // {
        //     $key = $request->input('key');
        //     return view('approval.paymentrequests.index', compact('paymentrequests', 'key'));
        // }
        // else
        //     return view('approval.paymentrequests.index', compact('paymentrequests'));

        return $this->searchmindexmy(request());
        // $paymentrequests = PaymentrequestsController::my();
        
        // $dtuser = Auth::user()->dtuser;

        // return view('approval.mindexmy', compact('paymentrequests', 'dtuser'));
    }

    public function mindexmying()
    {
        //
        // return $this->searchmindexmy(request());
        $paymentrequests = PaymentrequestsController::mying();
        

        return view('approval.mindexmy', compact('paymentrequests'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyed()
    {
        //
        // return $this->searchmindexmy(request());
        $paymentrequests = PaymentrequestsController::myed();
        

        return view('approval.mindexmy', compact('paymentrequests'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchmindexmy(Request $request)
    {
        //
//        $key = $request->input($request);
        $inputs = $request->all();

        $paymentrequests = PaymentrequestsController::my($request);
        // dd($paymentrequests);

        return view('approval.mindexmy', compact('paymentrequests', 'inputs'));
    }
    

    /**
     * 待我审批的.
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapproval()
    {
//        $reimbursements = ReimbursementsController::myapproval();
//        $paymentrequests = PaymentrequestsController::myapproval();
        $request = request();


        return $this->searchmindexmyapproval(request());
    }

    public function searchmindexmyapproval(Request $request)
    {
//        $reimbursements = ReimbursementsController::myapproval();
//        $paymentrequests = PaymentrequestsController::myapproval();
//        return view('approval.mindexmyapproval', compact('reimbursements', 'paymentrequests'));


        $key = $request->input('key');
        $paymenttype = $request->input('paymenttype');
        $projectname = $request->input('projectname');
        $productname = $request->input('productname');
        $suppliername = $request->input('suppliername');
        $inputs = $request->all();

        // 给出默认值
        if (!array_key_exists('approvaltype', $inputs))
            $inputs['approvaltype'] = '供应商付款';

        $approvaltype = Approvaltype::where('name', $inputs['approvaltype'])->first();
        $approvaltype_id = $approvaltype->id;

        // 获取当前登录人员的审批设置中的id
        $user = Auth::user();
        $userid = Auth::user()->id;
        
        // 特殊处理: if WuHL, set it to LiuYJ
        if ($inputs['approvaltype'] == "供应商付款" && Auth::user()->email == "wuhaolun@huaxing-east.com")
            $userid = User::where("email", "liuyujiao@huaxing-east.com")->first()->id;

        $ids_approversetting = Approversetting::where('approver_id', $userid)
            ->where('approvaltype_id', $approvaltype_id)
            ->select('id')->pluck('id');
        // 如果审批设置中没有设置人员，而是设置了部门和职位，那么也要加进去
        $ids_approversetting2 = [];
        if (isset($user->dept->id))
            $ids_approversetting2 = Approversetting::where('approver_id', '<', 1)->where('dept_id', $user->dept->id)->where('position', $user->position)->select('id')->pluck('id');
        $ids_approversetting = $ids_approversetting->merge($ids_approversetting2);

        $query = Paymentrequest::latest('created_at');
        $query->whereIn('approversetting_id', $ids_approversetting);

        if (strlen($key) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('descrip', 'like', '%'.$key.'%')
                ->orWhere('productname', 'like', '%'.$key.'%')
                ->pluck('id');
            $query->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
                $query->whereIn('supplier_id', $supplier_ids)
                    ->orWhereIn('pohead_id', $purchaseorder_ids);
            });
        }

        if (strlen($paymenttype) > 0)
        {
            $query->where('paymenttype', $paymenttype);
        }

        if (strlen($projectname) > 0)
        {
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('descrip', 'like', '%'.$projectname .'%')
                ->pluck('id');
            $query->whereIn('pohead_id', $purchaseorder_ids);
        }

        if (strlen($productname) > 0)
        {
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('productname', 'like', '%'.$productname .'%')
                ->pluck('id');
            $query->whereIn('pohead_id', $purchaseorder_ids);
        }

        if (strlen($suppliername) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$suppliername.'%')->pluck('id');
            $query->whereIn('supplier_id', $supplier_ids);
        }

        $paymentrequests = $query->select()->paginate(10);


//        if ('' == $key)
//        {
//            $paymentrequests = Paymentrequest::latest('created_at')->whereIn('approversetting_id', $ids_approversetting)->paginate(10);
//            $paymentrequestretracts = Paymentrequestretract::latest('created_at')->whereIn('approversetting_id', $ids_approversetting)->paginate(10);
//        }
//        else
//        {
//            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
//                ->where('descrip', 'like', '%'.$key.'%')
//                ->orWhere('productname', 'like', '%'.$key.'%')
//                ->pluck('id');
//
//            $paymentrequests = Paymentrequest::latest('created_at')
//                ->whereIn('approversetting_id', $ids_approversetting)
//                ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
//                    $query->whereIn('supplier_id', $supplier_ids)
//                        ->orWhereIn('pohead_id', $purchaseorder_ids);
//                })
//                ->select('paymentrequests.*')
//                ->paginate(10);
//
//            // todo: 以后实现对供应商审批撤回的关键字搜索功能
////            $paymentrequestretracts = Paymentrequestretract::latest('created_at')
////                ->whereIn('approversetting_id', $ids_approversetting)
////                ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
////                    $query->whereIn('supplier_id', $supplier_ids)
////                        ->orWhereIn('pohead_id', $purchaseorder_ids);
////                })
////                ->select('paymentrequests.*')
////                ->paginate(10);
//        }

//        return $inputs;
//        return $paymentrequests->url(1);
//        return redirect('approval/mindexmyapproval')->with(array_except($inputs, '_token'));
//        $url = route('approval/mindexmyapproval',['id' => 1] );
//        dd($url);
//        return route('/approval/mindexmyapproval', $inputs);
//        return redirect()->route('approval.mindexmyapproval', $inputs)->with('reimbursements', 'paymentrequests', 'paymentrequestretracts', 'key', 'inputs');
//        return response()->view('approval.mindexmyapproval' , compact('reimbursements', 'paymentrequests', 'paymentrequestretracts', 'key', 'inputs'))->header('query', http_build_query($inputs));
        return view('approval.mindexmyapproval' , compact('reimbursements', 'paymentrequests', 'paymentrequestretracts', 'key', 'inputs'));
    }

    /**
     * 我已审批的.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapprovaled()
    {
        return $this->searchmindexmyapprovaled(request());

        // // 获取当前操作人员的报销审批层次
        // $userid = Auth::user()->id;        
        // $ids = [];      // 报销id数组
        // $ids_paymentrequest = [];      // 报销id数组

        //  // 获取需要我审批的报销id数组
        // $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
        //     ->select('reimbursements.id', 
        //         DB::raw('(select count(approver_id) from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id and reimbursementapprovals.approver_id=' . $userid . ' limit 1) as myapprovaled'))     // 最后一次审批的状态
        //     ->get();

        // foreach ($reimbursementids as $reimbursementid) {
        //     if ($reimbursementid->myapprovaled > 0)
        //         $ids = array_prepend($ids, $reimbursementid->id);
        // }

        // $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);
        
        // $ids_paymentrequest = Paymentrequestapproval::where('approver_id', $userid)->select('paymentrequest_id')->distinct()->pluck('paymentrequest_id');
        // $paymentrequests = Paymentrequest::latest('created_at')->whereIn('id', $ids_paymentrequest)->paginate(10);

        // return view('approval.mindexmyapprovaled', compact('reimbursements', 'paymentrequests'));
    }

    public function searchmindexmyapprovaled(Request $request)
    {
        $key = $request->input('key');
        $paymenttype = $request->input('paymenttype');
        $projectname = $request->input('projectname');
        $productname = $request->input('productname');
        $suppliername = $request->input('suppliername');
        $inputs = $request->all();

        // 获取当前操作人员的报销审批层次
        $userids = [];
        $userid = Auth::user()->id;
        array_push($userids, $userid);

        // 特殊处理: if WuHL, set it to LiuYJ
//        if ($inputs['approvaltype'] == "供应商付款" && Auth::user()->email == "wuhaolun@huaxing-east.com")
//            $userid = User::where("email", "liuyujiao@huaxing-east.com")->first()->id;
        if (Auth::user()->email == "wuhaolun@huaxing-east.com")
        {
            $userid = User::where("email", "liuyujiao@huaxing-east.com")->first()->id;
            array_push($userids, $userid);
        }
        elseif (Auth::user()->email == "liuyujiao@huaxing-east.com")
        {
            $userid = User::where("email", "wuhaolun@huaxing-east.com")->first()->id;
            array_push($userids, $userid);
        }

        $ids = [];      // 报销id数组
        $ids_paymentrequest = [];      // 报销id数组



        // $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);
        
        $ids_paymentrequest = Paymentrequestapproval::where('approver_id', $userid)->select('paymentrequest_id')->distinct()->pluck('paymentrequest_id');

        $query = Paymentrequest::latest('created_at');
//        $query->whereIn('id', $ids_paymentrequest);
        $query->whereExists(function ($query) use ($userid, $userids) {
            $query->select(DB::raw(1))
                ->from('paymentrequestapprovals')
//                ->whereRaw('paymentrequestapprovals.approver_id=' . $userid . ' and paymentrequestapprovals.paymentrequest_id=paymentrequests.id ');
                ->whereRaw('paymentrequestapprovals.approver_id in (' . implode(",", $userids) . ') and paymentrequestapprovals.paymentrequest_id=paymentrequests.id ');
        });

        if (strlen($key) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('descrip', 'like', '%'.$key.'%')
                ->orWhere('productname', 'like', '%'.$key.'%')
                ->pluck('id');
            $query->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
                $query->whereIn('supplier_id', $supplier_ids)
                    ->orWhereIn('pohead_id', $purchaseorder_ids);
            });
        }

        if (strlen($paymenttype) > 0)
        {
            $query->where('paymenttype', $paymenttype);
        }

        if (strlen($projectname) > 0)
        {
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('descrip', 'like', '%'.$projectname .'%')
                ->pluck('id');
            $query->whereIn('pohead_id', $purchaseorder_ids);
        }

        if (strlen($productname) > 0)
        {
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->where('productname', 'like', '%'.$productname .'%')
                ->pluck('id');
            $query->whereIn('pohead_id', $purchaseorder_ids);
        }

        if (strlen($suppliername) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$suppliername.'%')->pluck('id');
            $query->whereIn('supplier_id', $supplier_ids);
        }

        $paymentrequests = $query->select()->paginate(10);

//        if ('' == $key)
//            $paymentrequests = Paymentrequest::latest('created_at')->whereIn('id', $ids_paymentrequest)->paginate(10);
//        else
//        {
//            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
//                ->where('descrip', 'like', '%'.$key.'%')
//                ->orWhere('productname', 'like', '%'.$key.'%')
//                ->pluck('id');
//
//            $paymentrequests = Paymentrequest::latest('created_at')
//                ->whereIn('id', $ids_paymentrequest)
//                ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
//                    $query->whereIn('supplier_id', $supplier_ids)
//                        ->orWhereIn('pohead_id', $purchaseorder_ids);
//                })
//                ->select('paymentrequests.*')
//                ->paginate(10);
//        }

        return view('approval.mindexmyapprovaled', compact('reimbursements', 'paymentrequests', 'inputs'));
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

    public function bingdingtalk()
    {
        $data = DingTalkController::register_call_back_bpms();

        if ($data->errcode == "0")
            dd($data->errmsg);
        else
            dd($data->errcode . ': ' . $data->errmsg);
    }

    public static function mcitempurchase($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

//        $process_code = 'PROC-EF6YRO35P2-7MPMNW3BNO0R8DKYN8GX1-2EACCA5J-6';     // hyerp
//        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
        $process_code = config('custom.dingtalk.approval_processcode.mcitempurchase');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
        $approvers = $inputs['approvers'];
//        $approvers = $user->dtuserid;
        // if originator_user_id in approvers, skip pre approvers
        $approver_array = explode(',', $approvers);
        if (in_array($originator_user_id, $approver_array))
        {
            $offset = array_search($originator_user_id, $approver_array);
            $approver_array = array_slice($approver_array, $offset+1);
            $approvers = implode(",", $approver_array);
        }
        if ($approvers == "")
            $approvers = config('custom.dingtalk.default_approvers');       // wuceshi for test

        $detail_array = [];
        $mcitempurchase_items = json_decode($inputs['items_string']);
        foreach ($mcitempurchase_items as $value) {
            if ($value->item_id > 0)
            {
                $item_array = [
                    [
                        'name'      => '物品名称',
                        'value'     => $value->item_name,
                    ],
                    [
                        'name'      => '规格型号',
                        'value'     => $value->item_spec,
                    ],
                    [
                        'name'      => '尺寸',
                        'value'     => $value->size,
                    ],
                    [
                        'name'      => '单价（可不填）',
                        'value'     => $value->unitprice,
                    ],
                    [
                        'name'      => '单位',
                        'value'     => $value->unit,
                    ],
                    [
                        'name'      => '数量',
                        'value'     => $value->quantity . (strlen($value->unit_id) > 0 ? ' ' . $value->unit_name : ''),
                    ],
                    [
                        'name'      => '重量（吨）',
                        'value'     => $value->weight,
                    ],
                    [
                        'name'      => '备注',
                        'value'     => $value->remark,
                    ],
                ];
                array_push($detail_array, $item_array);
            }
        }
        $formdata = [
            [
                'name'      => '所属制造中心',
                'value'     => $inputs['manufacturingcenter'],
            ],
            [
                'name'      => '申购物品类型',
                'value'     => $inputs['itemtype'],
            ],
            [
                'name'      => '要求最晚到货时间',
                'value'     => $inputs['expirationdate'],
            ],
            [
                'name'      => '项目编号',
                'value'     => $inputs['sohead_number'],
            ],
            [
                'name'      => '项目名称',
                'value'     => $inputs['project_name'],
            ],
            [
                'name'      => '下发图纸审批单号',
                'value'     => $inputs['issuedrawing_numbers'],
            ],
            [
                'name'      => '总价（元）',
                'value'     => $inputs['totalprice'],
            ],
            [
                'name'      => '总重量',
                'value'     => $inputs['totalweight'],
            ],
            [
                'name'      => '采购物品详细用途',
                'value'     => $inputs['detailuse'],
            ],
            [
                'name'      => '上传图片',
                'value'     => $inputs['image_urls'],
            ],
            [
                'name'      => '明细',
                'value'     => json_encode($detail_array),
            ],
        ];
        $form_component_values = json_encode($formdata);
//        $form_component_values = str_replace('#', '%23', $form_component_values);
//        $form_component_values = str_replace(' ', '%20', $form_component_values);
//        dd(json_decode(json_decode($form_component_values)[9]->value));
//        Log::info('process_code: ' . $process_code);
//        Log::info('originator_user_id: ' . $originator_user_id);
//        Log::info('dept_id: ' . $dept_id);
//        Log::info('approvers: ' . $approvers);
//        Log::info('form_component_values: ' . $form_component_values);
        $params = compact('method', 'session', 'v', 'format',
            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'form_component_values');
        $data = [
//            'form_component_values' => $form_component_values,
        ];

//        Log::info(app_path());
        $c = new DingTalkClient();
        $req = new SmartworkBpmsProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
        $req->setApprovers($approvers);
        $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.' . $inputs['manufacturingcenter']);
        if (strlen($cc_list) == 0)
            $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.default');
        if ($cc_list <> "")
        {
            $req->setCcList($cc_list);
            $req->setCcPosition("FINISH");
        }
//        $form_component_values = new FormComponentValueVo();
//        $form_component_values->name="请假类型";
//        $form_component_values->value="事假";
//        $form_component_values->ext_value="总天数:1";
        $req->setFormComponentValues("$form_component_values");
        $response = $c->execute($req, $session);
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }

    public static function pppayment($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

//        $process_code = 'PROC-EF6YRO35P2-7MPMNW3BNO0R8DKYN8GX1-2EACCA5J-6';     // hyerp
//        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
        $process_code = config('custom.dingtalk.approval_processcode.pppayment');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
        $approvers = $inputs['approvers'];
//        $approvers = $user->dtuserid;
        // if originator_user_id in approvers, skip pre approvers
        $approver_array = explode(',', $approvers);
        if (in_array($originator_user_id, $approver_array))
        {
            $offset = array_search($originator_user_id, $approver_array);
            $approver_array = array_slice($approver_array, $offset+1);
            $approvers = implode(",", $approver_array);
        }
        if ($approvers == "")
            $approvers = config('custom.dingtalk.default_approvers');       // wuceshi for test

        $detail_array = [];
        $mcitempurchase_items = json_decode($inputs['items_string']);
        foreach ($mcitempurchase_items as $value) {
            if ($value->sohead_id > 0)
            {
                $item_array = [
                    [
                        'name'      => '所属项目编号',
                        'value'     => $value->sohead_number,
                    ],
                    [
                        'name'      => '所属项目名称',
                        'value'     => $value->project_name,
                    ],
                    [
                        'name'      => '制作概述',
                        'value'     => $value->productionoverview,
                    ],
                    [
                        'name'      => '吨位',
                        'value'     => $value->tonnage,
                    ],
                    [
                        'name'      => '图纸下发单号',
                        'value'     => $value->issuedrawing_numbers,
                    ],
                    [
                        'name'      => '上传质检签收单',
                        'value'     => $inputs[$value->imagesname],
                    ],
                ];
                array_push($detail_array, $item_array);
            }
        }
        $formdata = [
            [
                'name'      => '制作公司',
                'value'     => $inputs['productioncompany'],
            ],
            [
                'name'      => '设计部门',
                'value'     => $inputs['designdepartment'],
            ],
            [
                'name'      => '付款事由',
                'value'     => $inputs['paymentreason'],
            ],
            [
                'name'      => '总吨位',
                'value'     => $inputs['totaltonnage'],
            ],
            [
                'name'      => '发票开具情况',
                'value'     => $inputs['invoicingsituation'],
            ],
            [
                'name'      => '该加工单已付款总额',
                'value'     => $inputs['totalpaid'],
            ],
            [
                'name'      => '本次申请付款总额',
                'value'     => $inputs['amount'],
            ],
            [
                'name'      => '支付日期',
                'value'     => $inputs['paymentdate'],
            ],
            [
                'name'      => '支付对象',
                'value'     => $inputs['supplier_name'],
            ],
            [
                'name'      => '开户行',
                'value'     => $inputs['supplier_bank'],
            ],
            [
                'name'      => '银行账户',
                'value'     => $inputs['supplier_bankaccountnumber'],
            ],
            [
                'name'      => '加工明细',
                'value'     => json_encode($detail_array),
            ],
        ];
        $form_component_values = json_encode($formdata);
//        $form_component_values = str_replace('#', '%23', $form_component_values);
//        $form_component_values = str_replace(' ', '%20', $form_component_values);
//        dd(json_decode(json_decode($form_component_values)[9]->value));
//        Log::info('process_code: ' . $process_code);
//        Log::info('originator_user_id: ' . $originator_user_id);
//        Log::info('dept_id: ' . $dept_id);
//        Log::info('approvers: ' . $approvers);
        Log::info('form_component_values: ' . $form_component_values);
        $params = compact('method', 'session', 'v', 'format',
            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'form_component_values');
        $data = [
//            'form_component_values' => $form_component_values,
        ];

//        Log::info(app_path());
        $c = new DingTalkClient();
        $req = new SmartworkBpmsProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
        $req->setApprovers($approvers);
        $cc_list = config('custom.dingtalk.approversettings.pppayment.cc_list.' . $inputs['designdepartment']);
        if (strlen($cc_list) == 0)
            $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.default');
        if ($cc_list <> "")
        {
            $req->setCcList($cc_list);
            $req->setCcPosition("FINISH");
        }
//        $form_component_values = new FormComponentValueVo();
//        $form_component_values->name="请假类型";
//        $form_component_values->value="事假";
//        $form_component_values->ext_value="总天数:1";
        $req->setFormComponentValues("$form_component_values");
        $response = $c->execute($req, $session);
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }
}
