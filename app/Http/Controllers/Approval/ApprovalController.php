<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\util\HttpDingtalkEco;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\domain\FormComponentValueVo;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCreateRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\SmartworkBpmsProcessinstanceCreateRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\SmartworkBpmsProcessinstanceListRequest;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Paymentrequestretract;
use App\Models\Approval\Pppayment;
use App\Models\Approval\Pppaymentitem;
use App\Models\Sales\Project_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Approval\Reimbursement;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestapproval;
use Auth, DB, Log, Datatables, Excel;
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
//        $inputs = request()->all();
//
//        if (!array_key_exists('approvaltype', $inputs))
//            $inputs['approvaltype'] = '供应商付款';
//        $approvaltype = $inputs['approvaltype'];
         return $this->searchmindexmy(request());
//        $paymentrequests = PaymentrequestsController::mying();
//
//
//        return view('approval.mindexmy', compact('paymentrequests'));
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
//        $paymentrequests = PaymentrequestsController::myed();
        return $this->searchmindexmyed(request());

//        return view('approval.mindexmy', compact('paymentrequests'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchmindexmy(Request $request)
    {
        //
        $key = $request->input($request);
        $inputs = $request->all();

        if (!array_key_exists('approvaltype', $inputs))
            $inputs['approvaltype'] = '供应商付款';
        $approvaltype = $inputs['approvaltype'];

        $items = null;
        if ($approvaltype == '供应商付款')
        {
            $items = PaymentrequestsController::my($request);
//            return view('approval.mindexmy', compact('paymentrequests', 'inputs'));
        }
        elseif ($approvaltype == '下发图纸')
        {
            $items = IssuedrawingController::my($request);
            return view('approval.mindexmy', compact('items', 'inputs'));
        }
        return view('approval.mindexmy', compact('items', 'inputs'));
    }

    public function searchmindexmyed(Request $request)
    {
        //
        $key = $request->input($request);
        $inputs = $request->all();

        if (!array_key_exists('approvaltype', $inputs))
            $inputs['approvaltype'] = '供应商付款';
        $approvaltype = $inputs['approvaltype'];

        $items = null;
        if ($approvaltype == '供应商付款')
        {
            $items = PaymentrequestsController::myed($request->input('key'));
//            return view('approval.mindexmy', compact('paymentrequests', 'inputs'));
        }
        elseif ($approvaltype == '下发图纸')
        {
            $items = IssuedrawingController::myed($request);
        }
        return view('approval.mindexmyed', compact('items', 'inputs'));
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
        // 特殊情况：
        // 如果是候S，可以看到供应商付款的第4级审批
        if ($user->id == 123)
            $ids_approversetting = $ids_approversetting->merge([11]);

        // 如果审批设置中没有设置人员，而是设置了部门和职位，那么也要加进去
        $ids_approversetting2 = [];
        if (isset($user->dept->id))
            $ids_approversetting2 = Approversetting::where('approver_id', '<', 1)->where('dept_id', $user->dept->id)->where('position', $user->position)->select('id')->pluck('id');
        $ids_approversetting = $ids_approversetting->merge($ids_approversetting2);

        $query = Paymentrequest::latest('created_at');
        $query->whereIn('approversetting_id', $ids_approversetting);
//        Log::info('ids_approversetting: ' . $ids_approversetting);

        if (strlen($key) > 0)
        {
            // 为了加快速度，将查询方式改成 whereRaw
            $query->whereRaw("(supplier_id in (select id from hxcrm2016..vsupplier where name like '%" . $key . "%') or pohead_id in (select id from hxcrm2016..vpurchaseorder where descrip like '%" . $key . "%' or productname like '%" . $key . "%'))");

//            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
//                ->where('descrip', 'like', '%'.$key.'%')
//                ->orWhere('productname', 'like', '%'.$key.'%')
//                ->pluck('id');
//            $query->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
//                $query->whereIn('supplier_id', $supplier_ids)
//                    ->orWhereIn('pohead_id', $purchaseorder_ids);
//            });
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

        $items = $query->select()->paginate(10);


//        return $inputs;
//        return $paymentrequests->url(1);
//        return redirect('approval/mindexmyapproval')->with(array_except($inputs, '_token'));
//        $url = route('approval/mindexmyapproval',['id' => 1] );
//        dd($url);
//        return route('/approval/mindexmyapproval', $inputs);
//        return redirect()->route('approval.mindexmyapproval', $inputs)->with('reimbursements', 'paymentrequests', 'paymentrequestretracts', 'key', 'inputs');
//        return response()->view('approval.mindexmyapproval' , compact('reimbursements', 'paymentrequests', 'paymentrequestretracts', 'key', 'inputs'))->header('query', http_build_query($inputs));
        return view('approval.mindexmyapproval' , compact('reimbursements', 'items', 'paymentrequestretracts', 'key', 'inputs'));
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

        if (!array_key_exists('approvaltype', $inputs))
            $inputs['approvaltype'] = '供应商付款';

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

        $query = Paymentrequest::latest('updated_at');
//        $query->whereIn('id', $ids_paymentrequest);
        $query->whereExists(function ($query) use ($userid, $userids) {
            $query->select(DB::raw(1))
                ->from('paymentrequestapprovals')
                ->whereRaw('paymentrequestapprovals.approver_id in (' . implode(",", $userids) . ') and paymentrequestapprovals.paymentrequest_id=paymentrequests.id ');
        });

        if (strlen($key) > 0)
        {
            // 通过 leftJoin 和 采购订单商品.goods_name 来查找，加快查找速度
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
                ->leftJoin('采购订单商品', '采购订单商品.order_id', '=', 'vpurchaseorder.id')
                ->where('descrip', 'like', '%'.$key.'%')
                ->orWhere('采购订单商品.goods_name', 'like', '%'.$key.'%')
//                ->orWhere('productname', 'like', '%'.$key.'%')
                ->pluck('vpurchaseorder.id');
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
            $query->leftJoin('hxcrm2016.dbo.vpurchaseorder', 'hxcrm2016.dbo.vpurchaseorder.id', '=', 'paymentrequests.pohead_id')
                ->leftJoin('hxcrm2016.dbo.采购订单商品', '采购订单商品.order_id', '=', 'vpurchaseorder.id')
                ->where('采购订单商品.goods_name', 'like', '%'.$productname.'%');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
//                ->leftJoin('采购订单商品', '采购订单商品.order_id', '=', 'vpurchaseorder.id')
//                ->where('采购订单商品.goods_name', 'like', '%'.$key.'%')
////                ->where('productname', 'like', '%'.$productname .'%')
//                ->pluck('vpurchaseorder.id');
//            $query->whereIn('pohead_id', $purchaseorder_ids);
        }

        if (strlen($suppliername) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$suppliername.'%')->pluck('id');
            $query->whereIn('supplier_id', $supplier_ids);
        }

        $items = $query->select('paymentrequests.*')->distinct()->paginate(10);

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

        return view('approval.mindexmyapprovaled', compact('reimbursements', 'items', 'inputs'));
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
        $format = 'json';
        $v = '2.0';

        if ($inputs['syncdtdesc'] == "许昌")
        {
            $session = DingTalkController::getAccessToken_appkey();
            $process_code = config('custom.dingtalk.hx_henan.approval_processcode.mcitempurchase');
            $originator_user_id = $user->dtuser2->userid;
            $departmentList = json_decode($user->dtuser2->department);
            $cc_list = config('custom.dingtalk.hx_henan.approversettings.mcitempurchase.cc_list.' . $inputs['manufacturingcenter']);
            if (strlen($cc_list) == 0)
                $cc_list = config('custom.dingtalk.hx_henan.approversettings.mcitempurchase.cc_list.default');
        }
        else
        {
            $session = DingTalkController::getAccessToken();
            $process_code = config('custom.dingtalk.approval_processcode.mcitempurchase');
            $originator_user_id = $user->dtuserid;
            $departmentList = json_decode($user->dtuser->department);
            $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.' . $inputs['manufacturingcenter']);
            if (strlen($cc_list) == 0)
                $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.default');
        }
//        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
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
                        'name'      => '材质',
                        'value'     => $value->material,
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
                'name'      => '项目吨数',
                'value'     => $inputs['projecttonnage'],
            ],
            [
                'name'      => '下发图纸审批单号',
                'value'     => $inputs['issuedrawing_numbers'],
            ],
            [
                'name'      => '下图单对应吨数',
                'value'     => $inputs['issuedrawing_weights'],
            ],
            [
                'name'      => '下图单制作概述',
                'value'     => $inputs['issuedrawing_overviews'],
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
                'name'      => '上传文件',
                'value'     => $inputs['fileattachments_url'],
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
//        $session = '';
        if ($inputs['syncdtdesc'] == "许昌")
            $session = DingTalkController::getAccessToken_appkey();
        else
            $session = DingTalkController::getAccessToken();
//        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        if ($inputs['syncdtdesc'] == "许昌")
            $process_code = config('custom.dingtalk.hx_henan.approval_processcode.pppayment');
        else
            $process_code = config('custom.dingtalk.approval_processcode.pppayment');
        if ($inputs['syncdtdesc'] == "许昌")
        {
            $originator_user_id = $user->dtuser2->userid;
            $departmentList = json_decode($user->dtuser2->department);
        }
        else
        {
            $originator_user_id = $user->dtuserid;
            $departmentList = json_decode($user->dtuser->department);
        }
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
//        if ($inputs['syncdtdesc'] == "许昌")
//            $approvers = "04090710367573";
//        else
//            $approvers = $inputs['approvers'];
        $approvers = $inputs['approvers'];
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
                        'name'      => '地区',
                        'value'     => $value->area,
                    ],
                    [
                        'name'      => '类型',
                        'value'     => $value->type,
                    ],
                    [
                        'name'      => '奖金明细',
                        'value'     => $inputs[$value->unitprice_inputname],
                    ],
                    [
                        'name'      => '合计',
                        'value'     => $inputs[$value->totalprice_inputname],
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
        if ($inputs['syncdtdesc'] == "许昌")
        {
            $cc_list = config('custom.dingtalk.hx_henan.approversettings.pppayment.cc_list.' . $inputs['productioncompany']);
            if (strlen($cc_list) == 0)
                $cc_list = config('custom.dingtalk.hx_henan.approversettings.pppayment.cc_list.default');
        }
        else
        {
            $cc_list = config('custom.dingtalk.approversettings.pppayment.cc_list.' . $inputs['productioncompany']);
            if (strlen($cc_list) == 0)
                $cc_list = config('custom.dingtalk.approversettings.pppayment.cc_list.default');
        }
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

    public function issuedrawingpurchasedetail()
    {
        $issuedrawings = Issuedrawing::all();
        $mcitempurchases = Mcitempurchase::all();
        $pppayments = Pppayment::all();

        return view('approval/reports2/issuedrawingpurchasedetail');
    }

    public function issuedrawingjson(Request $request, $sohead_id = 0, $factory = '', $project_id = 0)
    {
        $query = Issuedrawing::whereRaw('1=1');
        $query->where('status', 0);
        if ($request->has('sohead_id'))
            $query->where('sohead_id', $request->get('sohead_id'));
        elseif ($sohead_id > 0)
            $query->where('sohead_id', $sohead_id);
        elseif (strlen($factory) > 0)
            $query->where('productioncompany', 'like', '%' . $factory . '%');

        if ($project_id > 0)
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $project_id)->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

        if ($request->has('project_id'))
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $request->get('project_id'))->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

        if ($request->has('issuedrawingdatestart') && $request->has('issuedrawingdateend')) {
            $query->whereRaw('issuedrawings.created_at between \'' . $request->get('issuedrawingdatestart') . '\' and \'' . $request->get('issuedrawingdateend') . '\'');
        }

        $query->leftJoin('users', 'users.id', '=', 'issuedrawings.applicant_id');

//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');



        return Datatables::of($query->select(['issuedrawings.*', Db::raw('convert(varchar(100), issuedrawings.created_at, 23) as created_date'), 'users.name as applicant']))
            ->filterColumn('created_at', function ($query) use ($request) {
                $keyword = $request->get('search')['value'];
                $query->whereRaw('CONVERT(varchar(100), issuedrawings.created_at, 23) like \'%' . $keyword . '%\'');
            })
            ->filterColumn('applicant', function ($query) use ($request) {
                $keyword = $request->get('search')['value'];
                $query->whereRaw('users.name like \'%' . $keyword . '%\'');
            })
//            ->editColumn('created_at1', '{{ substr($created_at, 0, 10) }}' )
//            ->filter(function ($query) use ($request) {
//                if ($request->has('issuedrawingdatestart') && $request->has('issuedrawingdateend')) {
//                    $query->whereRaw('issuedrawings.created_at between \'' . $request->get('issuedrawingdatestart') . '\' and \'' . $request->get('issuedrawingdateend') . '\'');
//                }
//            })
//            ->addColumn('applicant', function (Issuedrawing $issuedrawing) {
//                return $issuedrawing->applicant->name;
//            })
//            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
//                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
//                ->orderColumn('created_at')
            ->make(true);
    }

    public function mcitempurchasejson(Request $request, $sohead_id = 0, $factory = '', $project_id = 0)
    {
        $query = Mcitempurchase::whereRaw('1=1');
        $query->where('status', 0);
        if ($request->has('sohead_id'))
            $query->where('sohead_id', $request->get('sohead_id'));
        elseif ($sohead_id > 0)
            $query->where('sohead_id', $sohead_id);
        elseif (strlen($factory) > 0)
            $query->where('manufacturingcenter', 'like', '%' . $factory . '%');
        elseif ($project_id > 0)
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $project_id)->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

        if ($request->has('project_id'))
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $request->get('project_id'))->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');


//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('mcitempurchases.*', Db::raw('convert(varchar(100), mcitempurchases.created_at, 23) as created_date')))
//            ->filter(function ($query) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend')) {
//                    $query->whereRaw('vreceiptpayment.date between \'' . $request->get('receivedatestart') . '\' and \'' . $request->get('receivedateend') . '\'');
//                }
//            })
            ->addColumn('totalweight', function (Mcitempurchase $mcitempurchase) {
                return $mcitempurchase->mcitempurchaseitems->sum('weight');
            })
//            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
//                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
            ->make(true);
    }

    public function pppaymentjson(Request $request, $sohead_id = 0, $factory = '', $project_id = 0)
    {
        $query = Pppaymentitem::whereRaw('1=1');
        $query->leftJoin('pppayments', 'pppaymentitems.pppayment_id', '=', 'pppayments.id');
        $query->where('pppayments.status', 0);
//        $query->where('status', 0);
//        if ($request->has('sohead_id'))
//            $query->where('sohead_id', $request->get('sohead_id'));

        if ($request->has('project_id'))
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $request->get('project_id'))->pluck('id');
            $query->whereIn('pppaymentitems.sohead_id', $sohead_ids);
        }

//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('pppaymentitems.*', Db::raw('convert(varchar(100), pppaymentitems.created_at, 23) as created_date'),
                'pppayments.productioncompany', 'pppayments.paymentdate'))
            ->filter(function ($query) use ($request, $sohead_id, $factory, $project_id) {
                if ($request->has('sohead_id')) {
                    $query->where('pppaymentitems.sohead_id', $request->get('sohead_id'));
                }
                elseif ($sohead_id > 0)
                    $query->where('pppaymentitems.sohead_id', $sohead_id);
                elseif (strlen($factory) > 0)
                    $query->where('pppayments.productioncompany', 'like', '%' . $factory . '%');
                elseif ($project_id > 0)
                {
                    $sohead_ids = Salesorder_hxold::where('project_id', $project_id)->pluck('id');
                    $query->whereIn('pppaymentitems.sohead_id', $sohead_ids);
                }
            })
//            ->addColumn('tonnage_paowan', function (Pppayment $pppayment) {
//                return $pppayment->pppaymentitems->where('type', '抛丸')->sum(function ($pppaymentitem) {
//                    return $pppaymentitem->pppaymentitemunitprices->sum('tonnage');
//                });
//            })
            ->addColumn('tonnage_paowan', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->type == "抛丸" ? $pppaymentitem->pppaymentitemunitprices->sum('tonnage') : 0.0;
            })
            ->addColumn('tonnage_youqi', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->type == "油漆" ? $pppaymentitem->pppaymentitemunitprices->sum('tonnage') : 0.0;
            })
            ->addColumn('tonnage_rengong', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->type == "人工" ? $pppaymentitem->pppaymentitemunitprices->sum('tonnage') : 0.0;
            })
            ->addColumn('tonnage_maohan', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->type == "铆焊" ? $pppaymentitem->pppaymentitemunitprices->sum('tonnage') : 0.0;
            })
            ->addColumn('tonnage_waixieyouqi', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->type == "外协油漆" ? $pppaymentitem->pppaymentitemunitprices->sum('tonnage') : 0.0;
            })
            ->addColumn('applicant', function (Pppaymentitem $pppaymentitem) {
                return $pppaymentitem->pppayment->applicant->name;
            })
//            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
//                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
            ->make(true);
    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function issuedrawingpurchasedetailexport(Request $request)
    {
        //
        $filename = "下图申购结算明细报表_按订单";
//        if ($request->has('sohead_id'))
//        {
//            $sohead = Salesorder_hxold::find($request->get('sohead_id'));
//            if ($sohead)
//                $filename = $sohead->projectjc;
//        }
        Excel::create($filename, function($excel) use ($request, $filename) {
            $sohead_ids = [];
            if ($request->has('sohead_id') && $request->get('sohead_id') > 0)
                array_push($sohead_ids, $request->get('sohead_id'));
            else
            {
                $sohead_ids = Issuedrawing::where('status', 0)->distinct()->pluck('sohead_id');
            }
            foreach ($sohead_ids as $sohead_id)
            {
                $sheetname = "Sheetname" . $sohead_id;
                $sohead = Salesorder_hxold::find($sohead_id);
                if ($sohead)
                    $sheetname = $sohead->projectjc;
                $excel->sheet($sheetname, function($sheet) use ($request, $sohead_id) {
                    // Sheet manipulation
                    $data = [];
                    $tonnagetotal_issuedrawing = 0.0;
                    $tonnagetotal_mcitempurchase = 0.0;
                    $tonnagetotal_pppayment = 0.0;
                    $tonnagetotal_pppayment_paowan = 0.0;
                    $tonnagetotal_pppayment_youqi = 0.0;
                    $tonnagetotal_pppayment_rengong = 0.0;
                    $tonnagetotal_pppayment_maohan = 0.0;
                    $tonnagetotal_out = 0.0;
                    $tonnagetotal_in = 0.0;

                    $issuedrawings = $this->issuedrawingjson($request, $sohead_id);
//                dd($issuedrawings->getData(true));
//                dd(json_decode($issuedrawings) );
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $temp = [];
                        $temp['下图日期']          = $value['created_date'];
                        $temp['下图吨数']                = (double)$value['tonnage'];
                        $temp['下图申请人']              = $value['applicant'];
                        $temp['下图制作公司']     = $value['productioncompany'];
                        $temp['下图概述']               = $value['overview'];

                        $temp['申购日期']         = '';
                        $temp['申购制造中心'] = '';
                        $temp['申购重量']          = '';
                        $temp['申购用途']            = '';

                        $temp['结算日期']             = '';
                        $temp['抛丸']           = '';
                        $temp['油漆']            = '';
                        $temp['人工']          = '';
                        $temp['铆焊']           = '';
                        $temp['结算制作公司']        = '';
                        $temp['结算制作概述']       = '';
                        $temp['结算支付日期']              = '';
                        $temp['结算申请人']                = '';
                        $temp['结算吨位']                  = '';
                        array_push($data, $temp);
                        $tonnagetotal_issuedrawing += $value['tonnage'];
                    }
                    $mcitempurchases = $this->mcitempurchasejson($request, $sohead_id);
                    $mcitempurchasesArray = $mcitempurchases->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($mcitempurchasesArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['申购日期']          = $value['created_date'];
                            $data[$key]['申购制造中心']  = $value['manufacturingcenter'];
                            $data[$key]['申购重量']           = $value['totalweight'];
                            $data[$key]['申购用途']             = $value['detailuse'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = $value['created_date'];
                            $temp['申购制造中心'] = $value['manufacturingcenter'];
                            $temp['申购重量']          = $value['totalweight'];
                            $temp['申购用途']            = $value['detailuse'];

                            $temp['结算日期']             = '';
                            $temp['抛丸']           = '';
                            $temp['油漆']            = '';
                            $temp['人工']          = '';
                            $temp['铆焊']           = '';
                            $temp['结算制作公司']        = '';
                            $temp['结算制作概述']       = '';
                            $temp['结算支付日期']              = '';
                            $temp['结算申请人']                = '';
                            $temp['结算吨位']                  = '';
                            array_push($data, $temp);
                        }
                        $tonnagetotal_mcitempurchase += $value['totalweight'];
                    }
                    $pppayments = $this->pppaymentjson($request, $sohead_id);
                    $pppaymentsArray = $pppayments->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($pppaymentsArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['结算日期']           = $value['created_date'];
                            $data[$key]['抛丸']         = $value['tonnage_paowan'];
                            $data[$key]['油漆']          = $value['tonnage_youqi'];
                            $data[$key]['人工']        = $value['tonnage_rengong'];
                            $data[$key]['铆焊']          = $value['tonnage_maohan'];
                            $data[$key]['结算制作公司']      = $value['productioncompany'];
                            $data[$key]['结算制作概述']     = $value['productionoverview'];
                            $data[$key]['结算支付日期']             = $value['paymentdate'];
                            $data[$key]['结算申请人']               = $value['applicant'];
                            $data[$key]['结算吨位']                 = $value['tonnage'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = '';
                            $temp['申购制造中心'] = '';
                            $temp['申购重量']          = '';
                            $temp['申购用途']            = '';

                            $temp['结算日期']             = $value['created_date'];
                            $temp['抛丸']           = $value['tonnage_paowan'];
                            $temp['油漆']            = $value['tonnage_youqi'];
                            $temp['人工']          = $value['tonnage_rengong'];
                            $temp['铆焊']           = $value['tonnage_maohan'];
                            $temp['结算制作公司']        = $value['productioncompany'];
                            $temp['结算制作概述']       = $value['productionoverview'];
                            $temp['结算支付日期']              = $value['paymentdate'];
                            $temp['结算申请人']                = $value['applicant'];
                            $temp['结算吨位']                  = $value['tonnage'];

//                            $temp = [];
//                            $temp['issuedrawing.created_date']          = '';
//                            $temp['issuedrawing.tonnage']                = '';
//                            $temp['issuedrawing.applicant']              = '';
//                            $temp['issuedrawing.productioncompany']     = '';
//                            $temp['issuedrawing.overview']               = '';
//
//                            $temp['mcitempurchase.created_date']         = '';
//                            $temp['mcitempurchase.manufacturingcenter'] = '';
//                            $temp['mcitempurchase.totalweight']          = '';
//                            $temp['mcitempurchase.detailuse']            = '';
//
//                            $temp['pppayment.created_date']             = $value['created_date'];
//                            $temp['pppayment.tonnage_paowan']           = $value['tonnage_paowan'];
//                            $temp['pppayment.tonnage_youqi']            = $value['tonnage_youqi'];
//                            $temp['pppayment.tonnage_rengong']          = $value['tonnage_rengong'];
//                            $temp['pppayment.tonnage_maohan']           = $value['tonnage_maohan'];
//                            $temp['pppayment.productioncompany']        = $value['productioncompany'];
//                            $temp['pppayment.productionoverview']       = $value['productionoverview'];
//                            $temp['pppayment.paymentdate']              = $value['paymentdate'];
//                            $temp['pppayment.applicant']                = $value['applicant'];
//                            $temp['pppayment.tonnage']                  = $value['tonnage'];

                            array_push($data, $temp);
                        }
                        $tonnagetotal_pppayment += $value['tonnage'];
                        $tonnagetotal_pppayment_paowan += $value['tonnage_paowan'];
                        $tonnagetotal_pppayment_youqi += $value['tonnage_youqi'];
                        $tonnagetotal_pppayment_rengong += $value['tonnage_rengong'];
                        $tonnagetotal_pppayment_maohan += $value['tonnage_maohan'];
                    }

                    $param = "@orderid=" . $sohead_id;
                    $sohead_outitems = DB::connection('sqlsrv')->select(' pGetOrderOutHeight ' . $param);
                    if (count($sohead_outitems) > 0 && isset($sohead_outitems[0]))
                        $tonnagetotal_out = $sohead_outitems[0]->heights / 1000.0;

                    $sohead_initems = DB::connection('sqlsrv')->select(' pGetOrderInHeight ' . $param);
                    if (count($sohead_initems) > 0 && isset($sohead_initems[0]))
                        $tonnagetotal_in = $sohead_initems[0]->heights / 1000.0;
//                    $sohead_outitems = json_decode(json_encode($sohead_outitems), true);

//                dd($data);
                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $totalrowcolor = "#00FF00";       // green
                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase,
                        $tonnagetotal_pppayment . "（其中抛丸" . $tonnagetotal_pppayment_paowan . "，油漆" . $tonnagetotal_pppayment_youqi . "，人工" . $tonnagetotal_pppayment_rengong . "，铆焊" . $tonnagetotal_pppayment_maohan . "）",
                        "领用" . $tonnagetotal_out, "入库" . $tonnagetotal_in
                        ]);
                    $sheet->row(count($data) + 2, function ($row) use ($totalrowcolor) {
                        $row->setBackground($totalrowcolor);
                    });
                });
            }

            // Set the title
            $excel->setTitle($filename);

            // Chain the setters
            $excel->setCreator('HXERP')
                ->setCompany('Huaxing East');

            // Call them separately
//            $excel->setDescription('A demonstration to change the file properties');

        })->export('xlsx');

        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // // $dompdf->loadHtml('hello world');
        // // $dompdf->set_option('isRemoteEnabled', true);
        // // $dompdf->loadHtmlFile(url('/approval/paymentrequests/25'));
        // $dompdf->loadHtmlFile('http://www.baidu.com');
        // // $html = file_get_contents('http://www.baidu.com');
        // // return $html;

        // // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser
        // $dompdf->stream();

        // return PDF::loadFile(url('/approval/paymentrequests/25'))->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

        // return 'ssss';
    }

    public function issuedrawingpurchasedetailexport2(Request $request)
    {
        //
        $filename = "下图申购结算明细报表_按工厂";
        Excel::create($filename, function($excel) use ($request, $filename) {
            $factoryList = ['无锡', '泰州', '胶州'];
//            $factoryList['无锡'] = [
//                'issuedrawing' => ['无锡电气生产部', '无锡生产中心', '无锡制造中心'],
//                'mcitempurchase' => ['']
//            ];
//            $factoryList['泰州'] = ['泰州生产中心'];
//            $factoryList['胶州'] = ['胶州生产中心'];
            foreach ($factoryList as $key => $factory)
            {
                $sheetname = $factory;
                $excel->sheet($sheetname, function($sheet) use ($request, $factory) {
                    // Sheet manipulation
                    $data = [];
                    $tonnagetotal_issuedrawing = 0.0;
                    $tonnagetotal_mcitempurchase = 0.0;
                    $tonnagetotal_pppayment = 0.0;
                    $tonnagetotal_pppayment_paowan = 0.0;
                    $tonnagetotal_pppayment_youqi = 0.0;
                    $tonnagetotal_pppayment_rengong = 0.0;
                    $tonnagetotal_pppayment_maohan = 0.0;

                    $issuedrawings = $this->issuedrawingjson($request, 0, $factory);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $temp = [];
                        $temp['下图日期']          = $value['created_date'];
                        $temp['下图吨数']                = $value['tonnage'];
                        $temp['下图申请人']              = $value['applicant'];
                        $temp['下图制作公司']     = $value['productioncompany'];
                        $temp['下图概述']               = $value['overview'];

                        $temp['申购日期']         = '';
                        $temp['申购制造中心'] = '';
                        $temp['申购重量']          = '';
                        $temp['申购用途']            = '';

                        $temp['结算日期']             = '';
                        $temp['抛丸']           = '';
                        $temp['油漆']            = '';
                        $temp['人工']          = '';
                        $temp['铆焊']           = '';
                        $temp['结算制作公司']        = '';
                        $temp['结算制作概述']       = '';
                        $temp['结算支付日期']              = '';
                        $temp['结算申请人']                = '';
                        $temp['结算吨位']                  = '';
                        array_push($data, $temp);
                        $tonnagetotal_issuedrawing += $value['tonnage'];
                    }
                    $mcitempurchases = $this->mcitempurchasejson($request, 0, $factory);
                    $mcitempurchasesArray = $mcitempurchases->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($mcitempurchasesArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['申购日期']          = $value['created_date'];
                            $data[$key]['申购制造中心']  = $value['manufacturingcenter'];
                            $data[$key]['申购重量']           = $value['totalweight'];
                            $data[$key]['申购用途']             = $value['detailuse'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = $value['created_date'];
                            $temp['申购制造中心'] = $value['manufacturingcenter'];
                            $temp['申购重量']          = $value['totalweight'];
                            $temp['申购用途']            = $value['detailuse'];

                            $temp['结算日期']             = '';
                            $temp['抛丸']           = '';
                            $temp['油漆']            = '';
                            $temp['人工']          = '';
                            $temp['铆焊']           = '';
                            $temp['结算制作公司']        = '';
                            $temp['结算制作概述']       = '';
                            $temp['结算支付日期']              = '';
                            $temp['结算申请人']                = '';
                            $temp['结算吨位']                  = '';
                            array_push($data, $temp);
                        }
                        $tonnagetotal_mcitempurchase += $value['totalweight'];
                    }
                    $pppayments = $this->pppaymentjson($request, 0, $factory);
                    $pppaymentsArray = $pppayments->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($pppaymentsArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['结算日期']           = $value['created_date'];
                            $data[$key]['抛丸']         = $value['tonnage_paowan'];
                            $data[$key]['油漆']          = $value['tonnage_youqi'];
                            $data[$key]['人工']        = $value['tonnage_rengong'];
                            $data[$key]['铆焊']          = $value['tonnage_maohan'];
                            $data[$key]['结算制作公司']      = $value['productioncompany'];
                            $data[$key]['结算制作概述']     = $value['productionoverview'];
                            $data[$key]['结算支付日期']             = $value['paymentdate'];
                            $data[$key]['结算申请人']               = $value['applicant'];
                            $data[$key]['结算吨位']                 = $value['tonnage'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = '';
                            $temp['申购制造中心'] = '';
                            $temp['申购重量']          = '';
                            $temp['申购用途']            = '';

                            $temp['结算日期']             = $value['created_date'];
                            $temp['抛丸']           = $value['tonnage_paowan'];
                            $temp['油漆']            = $value['tonnage_youqi'];
                            $temp['人工']          = $value['tonnage_rengong'];
                            $temp['铆焊']           = $value['tonnage_maohan'];
                            $temp['结算制作公司']        = $value['productioncompany'];
                            $temp['结算制作概述']       = $value['productionoverview'];
                            $temp['结算支付日期']              = $value['paymentdate'];
                            $temp['结算申请人']                = $value['applicant'];
                            $temp['结算吨位']                  = $value['tonnage'];
                            array_push($data, $temp);
                        }
                        $tonnagetotal_pppayment += $value['tonnage'];
                        $tonnagetotal_pppayment_paowan += $value['tonnage_paowan'];
                        $tonnagetotal_pppayment_youqi += $value['tonnage_youqi'];
                        $tonnagetotal_pppayment_rengong += $value['tonnage_rengong'];
                        $tonnagetotal_pppayment_maohan += $value['tonnage_maohan'];
                    }
                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $totalrowcolor = "#00FF00";       // green
                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase,
                        $tonnagetotal_pppayment . "（其中抛丸" . $tonnagetotal_pppayment_paowan . "，油漆" . $tonnagetotal_pppayment_youqi . "，人工" . $tonnagetotal_pppayment_rengong . "，铆焊" . $tonnagetotal_pppayment_maohan . "）"
                    ]);
                    $sheet->row(count($data) + 2, function ($row) use ($totalrowcolor) {
                        $row->setBackground($totalrowcolor);
                    });
                });
            }

            // Set the title
            $excel->setTitle($filename);

            // Chain the setters
            $excel->setCreator('HXERP')
                ->setCompany('Huaxing East');

            // Call them separately
//            $excel->setDescription('A demonstration to change the file properties');

        })->export('xlsx');
    }

    public function issuedrawingpurchasedetailexport3(Request $request)
    {
        //
        $filename = "下图申购结算明细报表_按项目";
        Excel::create($filename, function($excel) use ($request, $filename) {
            $project_ids = [];
            $sohead_ids = Issuedrawing::where('status', 0)->distinct()->pluck('sohead_id');
            if ($request->has('project_id'))
                array_push($project_ids, $request->get('project_id'));
            else
            {
//                $sohead_ids = Issuedrawing::where('status', 0)->distinct()->pluck('sohead_id');
                $project_ids = Salesorder_hxold::whereIn('id', $sohead_ids)->distinct()->pluck('project_id');
            }
//            $project_ids = Salesorder_hxold::whereIn('id', $sohead_ids)->distinct()->pluck('project_id');
            foreach ($project_ids as $project_id)
            {
                $sheetname = "Sheetname" . $project_id;
                $project = Project_hxold::find($project_id);
                if ($project)
                    $sheetname = $project->name;
                else
                    continue;
                $excel->sheet($sheetname, function($sheet) use ($request, $project_id) {
                    // Sheet manipulation
                    $data = [];
                    $tonnagetotal_issuedrawing = 0.0;
                    $tonnagetotal_mcitempurchase = 0.0;
                    $tonnagetotal_pppayment = 0.0;
                    $tonnagetotal_pppayment_paowan = 0.0;
                    $tonnagetotal_pppayment_youqi = 0.0;
                    $tonnagetotal_pppayment_rengong = 0.0;
                    $tonnagetotal_pppayment_maohan = 0.0;
                    $tonnagetotal_pppayment_waixieyouqi=0.0;
                    $tonnagetotal_out = 0.0;
                    $tonnagetotal_in = 0.0;

                    $issuedrawings = $this->issuedrawingjson($request, 0, '', $project_id);
//                dd($issuedrawings->getData(true));
//                dd(json_decode($issuedrawings) );
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $temp = [];
                        $temp['下图日期']          = $value['created_date'];
                        $temp['下图吨数']                = (double)$value['tonnage'];
                        $temp['下图申请人']              = $value['applicant'];
                        $temp['下图制作公司']     = $value['productioncompany'];
                        $temp['下图概述']               = $value['overview'];

                        $temp['申购日期']         = '';
                        $temp['申购制造中心'] = '';
                        $temp['申购重量']          = '';
                        $temp['申购用途']            = '';

                        $temp['结算日期']             = '';
                        $temp['抛丸']           = '';
                        $temp['油漆']            = '';
                        $temp['人工']          = '';
                        $temp['铆焊']           = '';
                        $temp['外协油漆']           = '';
                        $temp['结算制作公司']        = '';
                        $temp['结算制作概述']       = '';
                        $temp['结算支付日期']              = '';
                        $temp['结算申请人']                = '';
                        $temp['结算吨位']                  = '';
                        array_push($data, $temp);
                        $tonnagetotal_issuedrawing += $value['tonnage'];
                    }
                    $mcitempurchases = $this->mcitempurchasejson($request, 0, '', $project_id);
                    $mcitempurchasesArray = $mcitempurchases->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($mcitempurchasesArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['申购日期']          = $value['created_date'];
                            $data[$key]['申购制造中心']  = $value['manufacturingcenter'];
                            $data[$key]['申购重量']           = $value['totalweight'];
                            $data[$key]['申购用途']             = $value['detailuse'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = $value['created_date'];
                            $temp['申购制造中心'] = $value['manufacturingcenter'];
                            $temp['申购重量']          = $value['totalweight'];
                            $temp['申购用途']            = $value['detailuse'];

                            $temp['结算日期']             = '';
                            $temp['抛丸']           = '';
                            $temp['油漆']            = '';
                            $temp['人工']          = '';
                            $temp['铆焊']           = '';
                            $temp['外协油漆']           = '';
                            $temp['结算制作公司']        = '';
                            $temp['结算制作概述']       = '';
                            $temp['结算支付日期']              = '';
                            $temp['结算申请人']                = '';
                            $temp['结算吨位']                  = '';
                            array_push($data, $temp);
                        }
                        $tonnagetotal_mcitempurchase += $value['totalweight'];
                    }
                    $pppayments = $this->pppaymentjson($request, 0, '', $project_id);
                    $pppaymentsArray = $pppayments->getData(true)["data"];
                    $data_size = count($data);
                    foreach ($pppaymentsArray as $key => $value)
                    {
                        if ($data_size > $key)
                        {
                            $data[$key]['结算日期']           = $value['created_date'];
                            $data[$key]['抛丸']         = $value['tonnage_paowan'];
                            $data[$key]['油漆']          = $value['tonnage_youqi'];
                            $data[$key]['人工']        = $value['tonnage_rengong'];
                            $data[$key]['铆焊']          = $value['tonnage_maohan'];
                            $data[$key]['外协油漆']          = $value['tonnage_waixieyouqi'];
                            $data[$key]['结算制作公司']      = $value['productioncompany'];
                            $data[$key]['结算制作概述']     = $value['productionoverview'];
                            $data[$key]['结算支付日期']             = $value['paymentdate'];
                            $data[$key]['结算申请人']               = $value['applicant'];
                            $data[$key]['结算吨位']                 = $value['tonnage'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['下图日期']          = '';
                            $temp['下图吨数']                = '';
                            $temp['下图申请人']              = '';
                            $temp['下图制作公司']     = '';
                            $temp['下图概述']               = '';

                            $temp['申购日期']         = '';
                            $temp['申购制造中心'] = '';
                            $temp['申购重量']          = '';
                            $temp['申购用途']            = '';

                            $temp['结算日期']             = $value['created_date'];
                            $temp['抛丸']           = $value['tonnage_paowan'];
                            $temp['油漆']            = $value['tonnage_youqi'];
                            $temp['人工']          = $value['tonnage_rengong'];
                            $temp['铆焊']           = $value['tonnage_maohan'];
                            $temp['外协油漆']           = $value['tonnage_waixieyouqi'];
                            $temp['结算制作公司']        = $value['productioncompany'];
                            $temp['结算制作概述']       = $value['productionoverview'];
                            $temp['结算支付日期']              = $value['paymentdate'];
                            $temp['结算申请人']                = $value['applicant'];
                            $temp['结算吨位']                  = $value['tonnage'];
                            array_push($data, $temp);
                        }
                        $tonnagetotal_pppayment += $value['tonnage'];
                        $tonnagetotal_pppayment_paowan += $value['tonnage_paowan'];
                        $tonnagetotal_pppayment_youqi += $value['tonnage_youqi'];
                        $tonnagetotal_pppayment_rengong += $value['tonnage_rengong'];
                        $tonnagetotal_pppayment_maohan += $value['tonnage_maohan'];
                    }

                    $param = "@projectid=" . $project_id;
                    $sohead_outitems = DB::connection('sqlsrv')->select(' pGetOrderOutHeight ' . $param);
                    if (count($sohead_outitems) > 0 && isset($sohead_outitems[0]))
                        $tonnagetotal_out = $sohead_outitems[0]->heights / 1000.0;

                    $sohead_initems = DB::connection('sqlsrv')->select(' pGetOrderInHeight ' . $param);
                    if (count($sohead_initems) > 0 && isset($sohead_initems[0]))
                        $tonnagetotal_in = $sohead_initems[0]->heights / 1000.0;

//                    dd($data);
                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $totalrowcolor = "#00FF00";       // green
                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase,
                        $tonnagetotal_pppayment . "（其中抛丸" . $tonnagetotal_pppayment_paowan . "，油漆" . $tonnagetotal_pppayment_youqi . "，人工" . $tonnagetotal_pppayment_rengong . "，铆焊" . $tonnagetotal_pppayment_maohan ."，外协油漆" . $tonnagetotal_pppayment_waixieyouqi . "）",
                        "领用" . $tonnagetotal_out, "入库" . $tonnagetotal_in
                    ]);
                    $sheet->row(count($data) + 2, function ($row) use ($totalrowcolor) {
                        $row->setBackground($totalrowcolor);
                    });
                });
            }

            $excel->sheet('总表', function($sheet) use ($request, $project_ids) {
                // Sheet manipulation
                $data = [];

                $sheet->appendRow(["", "年份", "下图", "下图（无锡）", "下图（泰州）", "下图（胶州）", "下图（郎溪）", "采购", "结算抛丸", "结算油漆", "结算人工", "结算铆焊","结算外协油漆", "总领用", "无锡原料仓出库", "泰州原料仓出库", "胶州原料仓一厂出库", "胶州原料仓二厂出库", "郎溪原料仓出库",
                    "采购入库", "无锡原料仓入库", "泰州原料仓入库", "胶州原料仓一厂入库", "胶州原料仓二厂入库", "郎溪原料仓入库"]);

                foreach ($project_ids as $project_id)
                {
                    $project = Project_hxold::find($project_id);
                    if (!isset($project))
                        continue;


                    $data = [];
                    $tonnagetotal_issuedrawing = 0.0;
                    $tonnagetotal_issuedrawing_wx = 0.0;
                    $tonnagetotal_issuedrawing_tz = 0.0;
                    $tonnagetotal_issuedrawing_jz = 0.0;
                    $tonnagetotal_issuedrawing_lx = 0.0;
                    $tonnagetotal_mcitempurchase = 0.0;
                    $tonnagetotal_pppayment = 0.0;
                    $tonnagetotal_pppayment_paowan = 0.0;
                    $tonnagetotal_pppayment_youqi = 0.0;
                    $tonnagetotal_pppayment_rengong = 0.0;
                    $tonnagetotal_pppayment_maohan = 0.0;
                    $tonnagetotal_pppayment_waixieyouqi = 0.0;
                    $tonnagetotal_out = 0.0;
                    $tonnagetotal_out_wxylc = 0.0;
                    $tonnagetotal_out_tzylc = 0.0;
                    $tonnagetotal_out_jzylc1 = 0.0;
                    $tonnagetotal_out_jzylc2 = 0.0;
                    $tonnagetotal_out_lxylc = 0.0;
                    $tonnagetotal_in = 0.0;
                    $tonnagetotal_in_wxylc = 0.0;
                    $tonnagetotal_in_tzylc = 0.0;
                    $tonnagetotal_in_jzylc1 = 0.0;
                    $tonnagetotal_in_jzylc2 = 0.0;
                    $tonnagetotal_in_lxylc = 0.0;

                    $issuedrawings = $this->issuedrawingjson($request, 0, '', $project_id);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $tonnagetotal_issuedrawing += $value['tonnage'];
                    }
                    $issuedrawings = $this->issuedrawingjson($request, 0, '无锡生产中心', $project_id);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $tonnagetotal_issuedrawing_wx += $value['tonnage'];
                    }
                    $issuedrawings = $this->issuedrawingjson($request, 0, '泰州生产中心', $project_id);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $tonnagetotal_issuedrawing_tz += $value['tonnage'];
                    }
                    $issuedrawings = $this->issuedrawingjson($request, 0, '胶州生产中心', $project_id);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $tonnagetotal_issuedrawing_jz += $value['tonnage'];
                    }
                    $issuedrawings = $this->issuedrawingjson($request, 0, '郎溪生产中心', $project_id);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $tonnagetotal_issuedrawing_lx += $value['tonnage'];
                    }
                    $mcitempurchases = $this->mcitempurchasejson($request, 0, '', $project_id);
                    $mcitempurchasesArray = $mcitempurchases->getData(true)["data"];
                    foreach ($mcitempurchasesArray as $key => $value)
                    {
                        $tonnagetotal_mcitempurchase += $value['totalweight'];
                    }
                    $pppayments = $this->pppaymentjson($request, 0, '', $project_id);
                    $pppaymentsArray = $pppayments->getData(true)["data"];
                    foreach ($pppaymentsArray as $key => $value)
                    {
                        $tonnagetotal_pppayment += $value['tonnage'];
                        $tonnagetotal_pppayment_paowan += $value['tonnage_paowan'];
                        $tonnagetotal_pppayment_youqi += $value['tonnage_youqi'];
                        $tonnagetotal_pppayment_rengong += $value['tonnage_rengong'];
                        $tonnagetotal_pppayment_maohan += $value['tonnage_maohan'];
                        $tonnagetotal_pppayment_waixieyouqi += $value['tonnage_waixieyouqi'];
                    }

                    $param = "@projectid=" . $project_id;
                    $sohead_outitems = DB::connection('sqlsrv')->select(' pGetOrderOutHeight ' . $param);
                    if (count($sohead_outitems) > 0 && isset($sohead_outitems[0]))
                        $tonnagetotal_out = $sohead_outitems[0]->heights / 1000.0;

                    $param = "@warehouse_number='001',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_out_wxylc = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='003',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_out_tzylc = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='004',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_out_jzylc1 = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='008',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_out_jzylc2 = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='010',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_out_lxylc = $items[0]->heights / 1000.0;

                    $param = "@projectid=" . $project_id;
                    $sohead_initems = DB::connection('sqlsrv')->select(' pGetOrderInHeight ' . $param);
                    if (count($sohead_initems) > 0 && isset($sohead_initems[0]))
                        $tonnagetotal_in = $sohead_initems[0]->heights / 1000.0;

                    $param = "@warehouse_number='001',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderInHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_in_wxylc = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='003',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderInHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_in_tzylc = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='004',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderInHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_in_jzylc1 = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='008',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderInHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_in_jzylc2 = $items[0]->heights / 1000.0;

                    $param = "@warehouse_number='010',@projectid=" . $project_id;
                    $items = DB::connection('sqlsrv')->select(' pGetOrderInHeightByWarehouse ' . $param);
                    if (count($items) > 0 && isset($items[0]))
                        $tonnagetotal_in_lxylc = $items[0]->heights / 1000.0;

                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $year = Salesorder_hxold::where('project_id', $project->id)->select(DB::raw('MIN(YEAR(orderdate)) as year'))->pluck('year');
                    if (count($year) > 0)
                        $year = $year[0];

//                    $totalrowcolor = "#00FF00";       // green
//                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
//                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$project->name, $year, $tonnagetotal_issuedrawing, $tonnagetotal_issuedrawing_wx, $tonnagetotal_issuedrawing_tz, $tonnagetotal_issuedrawing_jz, $tonnagetotal_issuedrawing_lx, $tonnagetotal_mcitempurchase, $tonnagetotal_pppayment_paowan, $tonnagetotal_pppayment_youqi, $tonnagetotal_pppayment_rengong, $tonnagetotal_pppayment_maohan,$tonnagetotal_pppayment_waixieyouqi,
                        $tonnagetotal_out, $tonnagetotal_out_wxylc, $tonnagetotal_out_tzylc, $tonnagetotal_out_jzylc1, $tonnagetotal_out_jzylc2, $tonnagetotal_out_lxylc,
                        $tonnagetotal_in, $tonnagetotal_in_wxylc, $tonnagetotal_in_tzylc, $tonnagetotal_in_jzylc1, $tonnagetotal_in_jzylc2, $tonnagetotal_in_lxylc
                    ]);
//                    $sheet->row(count($data) + 2, function ($row) use ($totalrowcolor) {
//                        $row->setBackground($totalrowcolor);
//                    });
//                    break;
                }

                $param = "@orderid=7550";
                $sohead_outitems = DB::connection('sqlsrv')->select(' pGetOrderOutHeight ' . $param);
                if (count($sohead_outitems) > 0 && isset($sohead_outitems[0]))
                    $tonnagetotal_out = $sohead_outitems[0]->heights / 1000.0;

                $tonnagetotal_out_wxylc = 0.0;
                $param = "@warehouse_number='001',@orderid=7550";
                $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                if (count($items) > 0 && isset($items[0]))
                    $tonnagetotal_out_wxylc = $items[0]->heights / 1000.0;

                $tonnagetotal_out_tzylc = 0.0;
                $param = "@warehouse_number='003',@orderid=7550";
                $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                if (count($items) > 0 && isset($items[0]))
                    $tonnagetotal_out_tzylc = $items[0]->heights / 1000.0;

                $tonnagetotal_out_jzylc1 = 0.0;
                $param = "@warehouse_number='004',@orderid=7550";
                $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                if (count($items) > 0 && isset($items[0]))
                    $tonnagetotal_out_jzylc1 = $items[0]->heights / 1000.0;

                $tonnagetotal_out_jzylc2 = 0.0;
                $param = "@warehouse_number='008',@orderid=7550";
                $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                if (count($items) > 0 && isset($items[0]))
                    $tonnagetotal_out_jzylc2 = $items[0]->heights / 1000.0;

                $tonnagetotal_out_lxylc = 0.0;
                $param = "@warehouse_number='010',@orderid=7550";
                $items = DB::connection('sqlsrv')->select(' pGetOrderOutHeightByWarehouse ' . $param);
                if (count($items) > 0 && isset($items[0]))
                    $tonnagetotal_out_lxylc = $items[0]->heights / 1000.0;

                $param = "@orderid=7550";
                $sohead_initems = DB::connection('sqlsrv')->select(' pGetOrderInHeight ' . $param);
                if (count($sohead_initems) > 0 && isset($sohead_initems[0]))
                    $tonnagetotal_in = $sohead_initems[0]->heights / 1000.0;

                $sheet->appendRow(["厂部管理费用", "", "", "", "", "", "", "", "", "", "", "",
                    $tonnagetotal_out, $tonnagetotal_out_wxylc, $tonnagetotal_out_tzylc, $tonnagetotal_out_jzylc1, $tonnagetotal_out_jzylc2, $tonnagetotal_out_lxylc, $tonnagetotal_in
                ]);
            });

            // Set the title
            $excel->setTitle($filename);

            // Chain the setters
            $excel->setCreator('HXERP')
                ->setCompany('Huaxing East');

            // Call them separately
//            $excel->setDescription('A demonstration to change the file properties');

        })->export('xlsx');
    }

    public static function processinstance_listids_issuedrawing($startTime, $endTime)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
//        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.issuedrawing');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);

//        $form_component_values = str_replace('#', '%23', $form_component_values);
//        $form_component_values = str_replace(' ', '%20', $form_component_values);
//        dd(json_decode(json_decode($form_component_values)[9]->value));
//        Log::info('process_code: ' . $process_code);

        $startTime = $startTime->timestamp * 1000;
        $endTime = $endTime->timestamp * 1000;

//        Log::info($startTime);
//        Log::info($endTime);
        $c = new DingTalkClient();
        $req = new SmartworkBpmsProcessinstanceListRequest();
        $req->setProcessCode($process_code);
        $req->setStartTime("$startTime");
        $req->setEndTime("$endTime");
//        $req->setSize(10);

        $response = $c->execute($req, $session);
//        Log::info(json_encode($response));
        return $response;
//        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);


    }

    public static function processinstance_listids($approvaltype, $startTime, $endTime)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
//        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.' . $approvaltype);
//        $originator_user_id = $user->dtuserid;
//        $departmentList = json_decode($user->dtuser->department);
//        $dept_id = 0;
//        if (count($departmentList) > 0)
//            $dept_id = array_first($departmentList);


        $startTime = $startTime->timestamp * 1000;
        $endTime = $endTime->timestamp * 1000;

//        Log::info($startTime);
//        Log::info($endTime);
        $c = new DingTalkClient();
        $req = new SmartworkBpmsProcessinstanceListRequest();
        $req->setProcessCode($process_code);
        $req->setStartTime("$startTime");
        $req->setEndTime("$endTime");
//        $req->setSize(10);

        $response = $c->execute($req, $session);
//        Log::info(json_encode($response));
        return $response;
//        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

    }

    public static function projectsitepurchase($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.projectsitepurchase');
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
        $projectsitepurchase_items = json_decode($inputs['items_string']);
        foreach ($projectsitepurchase_items as $value) {
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
                        'name'      => '单位',
                        'value'     => $value->unit,
                    ],
                    [
                        'name'      => '品牌',
                        'value'     => $value->brand,
                    ],
                    [
                        'name'      => '计价单位',
                        'value'     => strlen($value->unit_id) > 0 ? ' ' . $value->unit_name : '',
                    ],
                    [
                        'name'      => '数量',
                        'value'     => $value->quantity,
                    ],
                    [
                        'name'      => '单价（元）',
                        'value'     => $value->unitprice,
                    ],
                    [
                        'name'      => '金额（元）',
                        'value'     => $value->price,
                    ],
                ];
                array_push($detail_array, $item_array);
            }
        }
        $formdata = [
            [
                'name'      => '采购公司',
                'value'     => $inputs['purchasecompany_name'],
            ],
            [
                'name'      => '工程名称',
                'value'     => $inputs['project_name'],
            ],
            [
                'name'      => '项目订单编号',
                'value'     => $inputs['sohead_number'],
            ],
            [
                'name'      => '订单所属销售经理',
                'value'     => $inputs['sohead_salesmanager'],
            ],
            [
                'name'      => '项目类型',
                'value'     => $inputs['projecttype'],
            ],
            [
                'name'      => '采购是否涉及供应商扣款',
                'value'     => $inputs['vendordeduction_descrip'],
            ],
            [
                'name'      => '关联相关扣款审批单',
                'value'     => $inputs['associatedapprovals'],
            ],
            [
                'name'      => '订单所属设计部门',
                'value'     => $inputs['designdept'],
            ],
            [
                'name'      => '生产部门',
                'value'     => $inputs['productiondept'],
            ],
            [
                'name'      => '外协设备商全称',
                'value'     => $inputs['outsourcingcompany'],
            ],
            [
                'name'      => '采购类型',
                'value'     => $inputs['purchasetype'],
            ],
            [
                'name'      => 'EP项目安装费原因',
                'value'     => $inputs['epamountreason'],
            ],
            [
                'name'      => '采购原因',
                'value'     => $inputs['purchasereason'],
            ],
            [
                'name'      => '采购原因补充说明',
                'value'     => $inputs['remark'],
            ],
            [
                'name'      => '交通或运费（元）',
                'value'     => $inputs['freight'],
            ],
//            [
//                'name'      => '合计总金额',
//                'value'     => $inputs['totalprice'],
//            ],
            [
                'name'      => '支付方式',
                'value'     => $inputs['paymentmethod'],
            ],
            [
                'name'      => '发票情况',
                'value'     => $inputs['invoicesituation'],
            ],
            [
                'name'      => '公司名称',
                'value'     => $inputs['companyname'],
            ],
            [
                'name'      => '联系人',
                'value'     => $inputs['contact'],
            ],
            [
                'name'      => '联系方式',
                'value'     => $inputs['phonenumber'],
            ],
            [
                'name'      => '备注',
                'value'     => $inputs['otherremark'],
            ],
            [
                'name'      => '上传购买凭证',
                'value'     => $inputs['image_urls'],
            ],
            [
                'name'      => '上传工程采购EXCEL表',
                'value'     => $inputs['files_string'],
            ],
            [
                'name'      => '采购明细（一个流程不超过15条明细）',
                'value'     => json_encode($detail_array),
            ],
        ];
//        if (isset($inputs['epamountreason']) && strlen($inputs['epamountreason']) > 0)
//            array_push($formdata,
//                [
//                    'name'      => 'EP项目安装费原因',
//                    'value'     => $inputs['epamountreason'],
//                ]);
        $form_component_values = json_encode($formdata);
//        dd($form_component_values);
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
//        $req = new SmartworkBpmsProcessinstanceCreateRequest();
        $req = new OapiProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
//        $req->setApprovers($approvers);
//        $cc_list = config('custom.dingtalk.approversettings.projectsitepurchase.cc_list.' . $inputs['purchasetype']);
//        if (strlen($cc_list) == 0)
//            $cc_list = config('custom.dingtalk.approversettings.projectsitepurchase.cc_list.default', '');
//        if ($cc_list <> "")
//        {
//            $req->setCcList($cc_list);
//            $req->setCcPosition("FINISH");
//        }
        $req->setFormComponentValues("$form_component_values");

//        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
        $response = $c->execute($req, $session);
        Log::info(json_encode($response));
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }

//    public static function vendordeduction($inputs)
//    {
//        $user = Auth::user();
//        $method = 'dingtalk.smartwork.bpms.processinstance.create';
//        $session = DingTalkController::getAccessToken();
//        $timestamp = time('2017-07-19 13:06:00');
//        $format = 'json';
//        $v = '2.0';
//
//        $process_code = config('custom.dingtalk.approval_processcode.vendordeduction');
//        $originator_user_id = $user->dtuserid;
//        $departmentList = json_decode($user->dtuser->department);
//        $dept_id = 0;
//        if (count($departmentList) > 0)
//            $dept_id = array_first($departmentList);
//        $approvers = $inputs['approvers'];
//        // if originator_user_id in approvers, skip pre approvers
//        $approver_array = explode(',', $approvers);
//        if (in_array($originator_user_id, $approver_array))
//        {
//            $offset = array_search($originator_user_id, $approver_array);
//            $approver_array = array_slice($approver_array, $offset+1);
//            $approvers = implode(",", $approver_array);
//        }
//        if ($approvers == "")
//            $approvers = config('custom.dingtalk.default_approvers');
//
//        $detail_array = [];
//        $vendordeduction_items = json_decode($inputs['items_string']);
//        $totalprice = 0.0;
//        foreach ($vendordeduction_items as $value) {
//            if (strlen($value->itemname) > 0)
//            {
//                $item_array = [
//                    [
//                        'name'      => '设备名称',
//                        'value'     => $value->itemname,
//                    ],
//                    [
//                        'name'      => '规格',
//                        'value'     => $value->itemspec,
//                    ],
//                    [
//                        'name'      => '单位',
//                        'value'     => $value->itemunit,
//                    ],
//                    [
//                        'name'      => '数量',
//                        'value'     => $value->quantity,
//                    ],
//                    [
//                        'name'      => '单价',
//                        'value'     => $value->unitprice,
//                    ],
//                    [
//                        'name'      => '总额（元）',
//                        'value'     => $value->quantity * $value->unitprice,
//                    ],
//                ];
//                array_push($detail_array, $item_array);
//                $totalprice += $value->quantity * $value->unitprice;
//            }
//        }
//        $formdata = [
//            [
//                'name'      => '本次扣款所属项目名称',
//                'value'     => $inputs['pohead_descrip'],
//            ],
//            [
//                'name'      => '本次扣款所属项目编号',
//                'value'     => $inputs['sohead_number'],
//            ],
//            [
//                'name'      => '本次扣款外协合同编号',
//                'value'     => $inputs['pohead_number'],
//            ],
//            [
//                'name'      => '外协单位名称',
//                'value'     => $inputs['vendor_name'],
//            ],
//            [
//                'name'      => '外协单位所属种类',
//                'value'     => $inputs['outsourcingtype'],
//            ],
//            [
//                'name'      => '工艺主设部门',
//                'value'     => $inputs['techdepart'],
//            ],
//            [
//                'name'      => '扣款问题发生地',
//                'value'     => $inputs['problemlocation'],
//            ],
//            [
//                'name'      => '扣款原因',
//                'value'     => $inputs['reason'],
//            ],
//            [
//                'name'      => '申请扣款总金额（元）',
//                'value'     => $totalprice,
//            ],
//            [
//                'name'      => '备注',
//                'value'     => $inputs['remark'],
//            ],
//            [
//                'name'      => '供应商盖章或签字确认的文件',
//                'value'     => $inputs['fileattachments_url'],
//            ],
//            [
//                'name'      => '供应商确认的或执行通知义务的截图',
//                'value'     => $inputs['image_urls'],
//            ],
//            [
//                'name'      => '明细',
//                'value'     => json_encode($detail_array),
//            ],
//        ];
//        $form_component_values = json_encode($formdata);
////        dd(json_decode(json_decode($form_component_values)[9]->value));
////        Log::info('process_code: ' . $process_code);
////        Log::info('originator_user_id: ' . $originator_user_id);
////        Log::info('dept_id: ' . $dept_id);
////        Log::info('approvers: ' . $approvers);
////        Log::info('form_component_values: ' . $form_component_values);
//        $params = compact('method', 'session', 'v', 'format',
//            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'form_component_values');
//        $data = [
////            'form_component_values' => $form_component_values,
//        ];
//
////        Log::info(app_path());
//        $c = new DingTalkClient();
//        $req = new SmartworkBpmsProcessinstanceCreateRequest();
////        $req->setAgentId("41605932");
//        $req->setProcessCode($process_code);
//        $req->setOriginatorUserId($originator_user_id);
//        $req->setDeptId("$dept_id");
//        $req->setApprovers($approvers);
//        $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default');
//        if (strlen($cc_list) == 0)
//            $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default', '');
//        if ($cc_list <> "")
//        {
//            $req->setCcList($cc_list);
//            $req->setCcPosition("FINISH");
//        }
//        $req->setFormComponentValues("$form_component_values");
//
////        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
//        $response = $c->execute($req, $session);
//        return json_encode($response);
//        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
//        return response()->json($response);
//
////        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
////        $response = HttpDingtalkEco::post("", $params, json_encode($data));
//
//        return $response;
//    }

    public static function vendordeduction($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.vendordeduction');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
        $approvers = $inputs['approvers'];
        // if originator_user_id in approvers, skip pre approvers
        $approver_array = explode(',', $approvers);
        if (in_array($originator_user_id, $approver_array))
        {
            $offset = array_search($originator_user_id, $approver_array);
            $approver_array = array_slice($approver_array, $offset+1);
            $approvers = implode(",", $approver_array);
        }
        if ($approvers == "")
            $approvers = config('custom.dingtalk.default_approvers');

        $detail_array = [];
        $vendordeduction_items = json_decode($inputs['items_string']);
        $totalprice = 0.0;
        foreach ($vendordeduction_items as $value) {
            if (strlen($value->itemname) > 0)
            {
                $item_array = [
                    [
                        'name'      => '名称',
                        'value'     => $value->itemname,
                    ],
                    [
                        'name'      => '规格',
                        'value'     => $value->itemspec,
                    ],
                    [
                        'name'      => '单位',
                        'value'     => $value->itemunit,
                    ],
                    [
                        'name'      => '数量',
                        'value'     => $value->quantity,
                    ],
                    [
                        'name'      => '单价',
                        'value'     => $value->unitprice,
                    ],
                    [
                        'name'      => '总额（元）',
                        'value'     => $value->quantity * $value->unitprice,
                    ],
                ];
                array_push($detail_array, $item_array);
                $totalprice += $value->quantity * $value->unitprice;
            }
        }
        $formdata = [
            [
                'name'      => '本次扣款所属项目名称',
                'value'     => $inputs['pohead_descrip'],
            ],
            [
                'name'      => '本次扣款所属项目编号',
                'value'     => $inputs['sohead_number'],
            ],
            [
                'name'      => '本次扣款外协合同编号',
                'value'     => $inputs['pohead_number'],
            ],
            [
                'name'      => '外协单位名称',
                'value'     => $inputs['vendor_name'],
            ],
            [
                'name'      => '外协单位所属种类',
                'value'     => $inputs['outsourcingtype'],
            ],
            [
                'name'      => '工艺主设部门',
                'value'     => $inputs['techdepart'],
            ],
            [
                'name'      => '扣款问题发生地',
                'value'     => $inputs['problemlocation'],
            ],
            [
                'name'      => '扣款原因',
                'value'     => $inputs['reason'],
            ],
            [
                'name'      => '申请扣款总金额（元）',
                'value'     => $totalprice,
            ],
            [
                'name'      => '备注',
                'value'     => $inputs['remark'],
            ],
//            [
//                'name'      => '供应商盖章或签字确认的文件',
//                'value'     => $inputs['fileattachments_url'],
//            ],
            [
                'name'      => '供应商盖章或签字确认的文件',
                'value'     => $inputs['files_string'],
            ],
            [
                'name'      => '供应商确认的或执行通知义务短信截图',
                'value'     => $inputs['image_urls'],
            ],
            [
                'name'      => '关联增补审批单',
                'value'     => $inputs['associatedapprovals'],
            ],
            [
                'name'      => '明细',
                'value'     => json_encode($detail_array),
            ],
        ];
        $form_component_values = json_encode($formdata);
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
        $req = new OapiProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
//        $req->setApprovers($approvers);
//        $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default');
//        if (strlen($cc_list) == 0)
//            $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default', '');
//        if ($cc_list <> "")
//        {
//            $req->setCcList($cc_list);
//            $req->setCcPosition("FINISH");
//        }
        $req->setFormComponentValues("$form_component_values");

//        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
        $response = $c->execute($req, $session);
//        Log::info(json_encode($response));
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }

    public static function techpurchase($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.techpurchase');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
//        $approvers = $inputs['approvers'];
//        // if originator_user_id in approvers, skip pre approvers
//        $approver_array = explode(',', $approvers);
//        if (in_array($originator_user_id, $approver_array))
//        {
//            $offset = array_search($originator_user_id, $approver_array);
//            $approver_array = array_slice($approver_array, $offset+1);
//            $approvers = implode(",", $approver_array);
//        }
//        if ($approvers == "")
//            $approvers = config('custom.dingtalk.default_approvers');

        $detail_array = [];
        $vendordeduction_items = json_decode($inputs['items_string']);
        $totalprice = 0.0;
        foreach ($vendordeduction_items as $value) {
            if (strlen($value->item_name) > 0)
            {
                $item_array = [
                    [
                        'name'      => '商品类别',
                        'value'     => $value->item_type,
                    ],
                    [
                        'name'      => '商品名称',
                        'value'     => $value->item_name,
                    ],
                    [
                        'name'      => '商品型号',
                        'value'     => $value->item_spec,
                    ],
                    [
                        'name'      => '数量',
                        'value'     => $value->quantity,
                    ],
//                    [
//                        'name'      => '单价',
//                        'value'     => $value->unitprice,
//                    ],
                    [
                        'name'      => '单位',
                        'value'     => $value->unit,
                    ],
                    [
                        'name'      => '商品说明',
                        'value'     => $value->descrip,
                    ],
                ];
                array_push($detail_array, $item_array);
//                $totalprice += $value->quantity * $value->unitprice;
            }
        }
        $formdata = [
            [
                'name'      => '采购所属公司',
                'value'     => $inputs['purchasecompany_name'],
            ],
            [
                'name'      => '提交部门',
                'value'     => $inputs['submitdepart'],
            ],
            [
                'name'      => '要求采购到位日期',
                'value'     => $inputs['arrivaldate'],
            ],
            [
                'name'      => '项目名称',
                'value'     => $inputs['project_name'],
            ],
            [
                'name'      => '项目合同编号',
                'value'     => $inputs['sohead_number'],
            ],
            [
                'name'      => '订单所属销售经理',
                'value'     => $inputs['sohead_salesmanager'],
            ],
//            [
//                'name'      => '备注',
//                'value'     => $inputs['remark'],
//            ],
//            [
//                'name'      => '供应商盖章或签字确认的文件',
//                'value'     => $inputs['fileattachments_url'],
//            ],
            [
                'name'      => '上传技术规范书',
                'value'     => $inputs['files_string'],
            ],
//            [
//                'name'      => '供应商确认的或执行通知义务的截图',
//                'value'     => $inputs['image_urls'],
//            ],
//            [
//                'name'      => '关联增补审批单',
//                'value'     => $inputs['associatedapprovals'],
//            ],
            [
                'name'      => '采购明细',
                'value'     => json_encode($detail_array),
            ],
        ];
        $form_component_values = json_encode($formdata);
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
        $req = new OapiProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
//        $req->setApprovers($approvers);
//        $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default');
//        if (strlen($cc_list) == 0)
//            $cc_list = config('custom.dingtalk.approversettings.vendordeduction.cc_list.default', '');
//        if ($cc_list <> "")
//        {
//            $req->setCcList($cc_list);
//            $req->setCcPosition("FINISH");
//        }
        $req->setFormComponentValues("$form_component_values");

//        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
        $response = $c->execute($req, $session);
//        Log::info(json_encode($response));
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }

    public static function corporatepayment($inputs)
    {
        $user = Auth::user();
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.corporatepayment');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
//        $approvers = $inputs['approvers'];
//        // if originator_user_id in approvers, skip pre approvers
//        $approver_array = explode(',', $approvers);
//        if (in_array($originator_user_id, $approver_array))
//        {
//            $offset = array_search($originator_user_id, $approver_array);
//            $approver_array = array_slice($approver_array, $offset+1);
//            $approvers = implode(",", $approver_array);
//        }
//        if ($approvers == "")
//            $approvers = config('custom.dingtalk.default_approvers');       // wuceshi for test

        $formdata = [
            [
                'name'      => '职位',
                'value'     => $inputs['position'],
            ],
            [
                'name'      => '费用类型',
                'value'     => $inputs['amounttype'],
            ],
            [
                'name'      => '现场采购费类说明',
                'value'     => $inputs['remark'],
            ],
            [
                'name'      => '项目属于销售员',
                'value'     => $inputs['sohead_salesmanager'],
            ],
            [
                'name'      => '付款说明',
                'value'     => $inputs['remark'],
            ],
            [
                'name'      => '付款总额',
                'value'     => $inputs['amount'],
            ],
            [
                'name'      => '支付日期',
                'value'     => $inputs['paydate'],
            ],
            [
                'name'      => '付款方式',
                'value'     => $inputs['paymentmethod'],
            ],
            [
                'name'      => '支付单位全称',
                'value'     => $inputs['supplier_name'],
            ],
            [
                'name'      => '开户行及帐号',
                'value'     => $inputs['supplier_bank'] . ',' . $inputs['supplier_bankaccountnumber'],
            ],
//            [
//                'name'      => '交通或运费（元）',
//                'value'     => $inputs['freight'],
//            ],
//            [
//                'name'      => '支付方式',
//                'value'     => $inputs['paymentmethod'],
//            ],
//            [
//                'name'      => '发票情况',
//                'value'     => $inputs['invoicesituation'],
//            ],
//            [
//                'name'      => '公司名称',
//                'value'     => $inputs['companyname'],
//            ],
//            [
//                'name'      => '联系人',
//                'value'     => $inputs['contact'],
//            ],
//            [
//                'name'      => '联系方式',
//                'value'     => $inputs['phonenumber'],
//            ],
//            [
//                'name'      => '备注',
//                'value'     => $inputs['otherremark'],
//            ],
//            [
//                'name'      => '上传购买凭证',
//                'value'     => $inputs['image_urls'],
//            ],
            [
                'name'      => '附件',
                'value'     => $inputs['files_string'],
            ],
            [
                'name'      => '关联《工程采购》审批单',
                'value'     => $inputs['associated_approval_projectpurchase'],
            ],
        ];
        $form_component_values = json_encode($formdata);
//        dd($form_component_values);
//        Log::info('process_code: ' . $process_code);
//        Log::info('originator_user_id: ' . $originator_user_id);
//        Log::info('dept_id: ' . $dept_id);
//        Log::info('approvers: ' . $approvers);
//        Log::info('form_component_values: ' . $form_component_values);

//        Log::info(app_path());
        $c = new DingTalkClient();
//        $req = new SmartworkBpmsProcessinstanceCreateRequest();
        $req = new OapiProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");

        $req->setFormComponentValues("$form_component_values");

//        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
        $response = $c->execute($req, $session);
        Log::info(json_encode($response));
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }

    public static function additionsalesorder($inputs)
    {
        $user = Auth::user();
        $session = DingTalkController::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = config('custom.dingtalk.approval_processcode.corporatepayment');
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
//        $approvers = $inputs['approvers'];
//        // if originator_user_id in approvers, skip pre approvers
//        $approver_array = explode(',', $approvers);
//        if (in_array($originator_user_id, $approver_array))
//        {
//            $offset = array_search($originator_user_id, $approver_array);
//            $approver_array = array_slice($approver_array, $offset+1);
//            $approvers = implode(",", $approver_array);
//        }
//        if ($approvers == "")
//            $approvers = config('custom.dingtalk.default_approvers');       // wuceshi for test

        $formdata = [
            [
                'name'      => '职位',
                'value'     => $inputs['position'],
            ],
            [
                'name'      => '费用类型',
                'value'     => $inputs['amounttype'],
            ],
            [
                'name'      => '现场采购费类说明',
                'value'     => $inputs['remark'],
            ],
            [
                'name'      => '项目属于销售员',
                'value'     => $inputs['sohead_salesmanager'],
            ],
            [
                'name'      => '付款说明',
                'value'     => $inputs['remark'],
            ],
            [
                'name'      => '付款总额',
                'value'     => $inputs['amount'],
            ],
            [
                'name'      => '支付日期',
                'value'     => $inputs['paydate'],
            ],
            [
                'name'      => '付款方式',
                'value'     => $inputs['paymentmethod'],
            ],
            [
                'name'      => '支付单位全称',
                'value'     => $inputs['supplier_name'],
            ],
            [
                'name'      => '开户行及帐号',
                'value'     => $inputs['supplier_bank'] . ',' . $inputs['supplier_bankaccountnumber'],
            ],
//            [
//                'name'      => '交通或运费（元）',
//                'value'     => $inputs['freight'],
//            ],
//            [
//                'name'      => '支付方式',
//                'value'     => $inputs['paymentmethod'],
//            ],
//            [
//                'name'      => '发票情况',
//                'value'     => $inputs['invoicesituation'],
//            ],
//            [
//                'name'      => '公司名称',
//                'value'     => $inputs['companyname'],
//            ],
//            [
//                'name'      => '联系人',
//                'value'     => $inputs['contact'],
//            ],
//            [
//                'name'      => '联系方式',
//                'value'     => $inputs['phonenumber'],
//            ],
//            [
//                'name'      => '备注',
//                'value'     => $inputs['otherremark'],
//            ],
//            [
//                'name'      => '上传购买凭证',
//                'value'     => $inputs['image_urls'],
//            ],
            [
                'name'      => '附件',
                'value'     => $inputs['files_string'],
            ],
            [
                'name'      => '关联《工程采购》审批单',
                'value'     => $inputs['associated_approval_projectpurchase'],
            ],
        ];
        $form_component_values = json_encode($formdata);
//        dd($form_component_values);
//        Log::info('process_code: ' . $process_code);
//        Log::info('originator_user_id: ' . $originator_user_id);
//        Log::info('dept_id: ' . $dept_id);
//        Log::info('approvers: ' . $approvers);
//        Log::info('form_component_values: ' . $form_component_values);

//        Log::info(app_path());
        $c = new DingTalkClient();
//        $req = new SmartworkBpmsProcessinstanceCreateRequest();
        $req = new OapiProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");

        $req->setFormComponentValues("$form_component_values");

//        Log::info($originator_user_id . "\t" . $approvers . "\t" . $cc_list . "\t" . $dept_id);
        $response = $c->execute($req, $session);
        Log::info(json_encode($response));
        return json_encode($response);
        dd(json_encode($response, JSON_UNESCAPED_UNICODE));
        return response()->json($response);

//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        $response = HttpDingtalkEco::post("", $params, json_encode($data));

        return $response;
    }
}
