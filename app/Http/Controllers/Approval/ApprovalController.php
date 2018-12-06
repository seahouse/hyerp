<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\util\HttpDingtalkEco;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\domain\FormComponentValueVo;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\SmartworkBpmsProcessinstanceCreateRequest;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Paymentrequestretract;
use App\Models\Approval\Pppayment;
use App\Models\Approval\Pppaymentitem;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
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

        $items = $query->select()->paginate(10);


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

        $items = $query->select()->paginate(10);

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
//        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

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
        $cc_list = config('custom.dingtalk.approversettings.pppayment.cc_list.' . $inputs['designdepartment']);
        if (strlen($cc_list) == 0)
            $cc_list = config('custom.dingtalk.approversettings.pppayment.cc_list.default');
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

    public function issuedrawingjson(Request $request, $sohead_id = 0, $factory = '')
    {
        $query = Issuedrawing::whereRaw('1=1');
        $query->where('status', 0);
        if ($request->has('sohead_id'))
            $query->where('sohead_id', $request->get('sohead_id'));
        elseif ($sohead_id > 0)
            $query->where('sohead_id', $sohead_id);
        elseif (strlen($factory) > 0)
            $query->where('productioncompany', 'like', '%' . $factory . '%');

//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');


//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('issuedrawings.*', Db::raw('convert(varchar(100), issuedrawings.created_at, 23) as created_date')))
//            ->filter(function ($query) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend')) {
//                    $query->whereRaw('vreceiptpayment.date between \'' . $request->get('receivedatestart') . '\' and \'' . $request->get('receivedateend') . '\'');
//                }
//            })
            ->addColumn('applicant', function (Issuedrawing $issuedrawing) {
                return $issuedrawing->applicant->name;
            })
//            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
//                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
            ->make(true);
    }

    public function mcitempurchasejson(Request $request, $sohead_id = 0, $factory = '')
    {
        $query = Mcitempurchase::whereRaw('1=1');
        $query->where('status', 0);
        if ($request->has('sohead_id'))
            $query->where('sohead_id', $request->get('sohead_id'));
        elseif ($sohead_id > 0)
            $query->where('sohead_id', $sohead_id);
        elseif (strlen($factory) > 0)
            $query->where('manufacturingcenter', 'like', '%' . $factory . '%');
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

    public function pppaymentjson(Request $request, $sohead_id = 0, $factory = '')
    {
        $query = Pppaymentitem::whereRaw('1=1');
        $query->leftJoin('pppayments', 'pppaymentitems.pppayment_id', '=', 'pppayments.id');
        $query->where('pppayments.status', 0);
//        $query->where('status', 0);
//        if ($request->has('sohead_id'))
//            $query->where('sohead_id', $request->get('sohead_id'));


//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('pppaymentitems.*', Db::raw('convert(varchar(100), pppaymentitems.created_at, 23) as created_date'),
                'pppayments.productioncompany', 'pppayments.paymentdate'))
            ->filter(function ($query) use ($request, $sohead_id, $factory) {
                if ($request->has('sohead_id')) {
                    $query->where('pppaymentitems.sohead_id', $request->get('sohead_id'));
                }
                elseif ($sohead_id > 0)
                    $query->where('pppaymentitems.sohead_id', $sohead_id);
                elseif (strlen($factory) > 0)
                    $query->where('pppayments.productioncompany', 'like', '%' . $factory . '%');
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
        $filename = "下图申购结算明细报表";
//        if ($request->has('sohead_id'))
//        {
//            $sohead = Salesorder_hxold::find($request->get('sohead_id'));
//            if ($sohead)
//                $filename = $sohead->projectjc;
//        }
        Excel::create($filename, function($excel) use ($request, $filename) {
            $sohead_ids = [];
            if ($request->has('sohead_id'))
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

                    $issuedrawings = $this->issuedrawingjson($request, $sohead_id);
//                dd($issuedrawings->getData(true));
//                dd(json_decode($issuedrawings) );
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $temp = [];
                        $temp['issuedrawing.created_date']          = $value['created_date'];
                        $temp['issuedrawing.tonnage']                = $value['tonnage'];
                        $temp['issuedrawing.applicant']              = $value['applicant'];
                        $temp['issuedrawing.productioncompany']     = $value['productioncompany'];
                        $temp['issuedrawing.overview']               = $value['overview'];

                        $temp['mcitempurchase.created_date']         = '';
                        $temp['mcitempurchase.manufacturingcenter'] = '';
                        $temp['mcitempurchase.totalweight']          = '';
                        $temp['mcitempurchase.detailuse']            = '';

                        $temp['pppayment.created_date']             = '';
                        $temp['pppayment.tonnage_paowan']           = '';
                        $temp['pppayment.tonnage_youqi']            = '';
                        $temp['pppayment.tonnage_rengong']          = '';
                        $temp['pppayment.tonnage_maohan']           = '';
                        $temp['pppayment.productioncompany']        = '';
                        $temp['pppayment.productionoverview']       = '';
                        $temp['pppayment.paymentdate']              = '';
                        $temp['pppayment.applicant']                = '';
                        $temp['pppayment.tonnage']                  = '';
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
                            $data[$key]['mcitempurchase.created_date']          = $value['created_date'];
                            $data[$key]['mcitempurchase.manufacturingcenter']  = $value['manufacturingcenter'];
                            $data[$key]['mcitempurchase.totalweight']           = $value['totalweight'];
                            $data[$key]['mcitempurchase.detailuse']             = $value['detailuse'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['issuedrawing.created_date']          = '';
                            $temp['issuedrawing.tonnage']                = '';
                            $temp['issuedrawing.applicant']              = '';
                            $temp['issuedrawing.productioncompany']     = '';
                            $temp['issuedrawing.overview']               = '';

                            $temp['mcitempurchase.created_date']         = $value['created_date'];
                            $temp['mcitempurchase.manufacturingcenter'] = $value['manufacturingcenter'];
                            $temp['mcitempurchase.totalweight']          = $value['totalweight'];
                            $temp['mcitempurchase.detailuse']            = $value['detailuse'];

                            $temp['pppayment.created_date']             = '';
                            $temp['pppayment.tonnage_paowan']           = '';
                            $temp['pppayment.tonnage_youqi']            = '';
                            $temp['pppayment.tonnage_rengong']          = '';
                            $temp['pppayment.tonnage_maohan']           = '';
                            $temp['pppayment.productioncompany']        = '';
                            $temp['pppayment.productionoverview']       = '';
                            $temp['pppayment.paymentdate']              = '';
                            $temp['pppayment.applicant']                = '';
                            $temp['pppayment.tonnage']                  = '';
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
                            $data[$key]['pppayment.created_date']           = $value['created_date'];
                            $data[$key]['pppayment.tonnage_paowan']         = $value['tonnage_paowan'];
                            $data[$key]['pppayment.tonnage_youqi']          = $value['tonnage_youqi'];
                            $data[$key]['pppayment.tonnage_rengong']        = $value['tonnage_rengong'];
                            $data[$key]['pppayment.tonnage_maohan']          = $value['tonnage_maohan'];
                            $data[$key]['pppayment.productioncompany']      = $value['productioncompany'];
                            $data[$key]['pppayment.productionoverview']     = $value['productionoverview'];
                            $data[$key]['pppayment.paymentdate']             = $value['paymentdate'];
                            $data[$key]['pppayment.applicant']               = $value['applicant'];
                            $data[$key]['pppayment.tonnage']                 = $value['tonnage'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['issuedrawing.created_date']          = '';
                            $temp['issuedrawing.tonnage']                = '';
                            $temp['issuedrawing.applicant']              = '';
                            $temp['issuedrawing.productioncompany']     = '';
                            $temp['issuedrawing.overview']               = '';

                            $temp['mcitempurchase.created_date']         = '';
                            $temp['mcitempurchase.manufacturingcenter'] = '';
                            $temp['mcitempurchase.totalweight']          = '';
                            $temp['mcitempurchase.detailuse']            = '';

                            $temp['pppayment.created_date']             = $value['created_date'];
                            $temp['pppayment.tonnage_paowan']           = $value['tonnage_paowan'];
                            $temp['pppayment.tonnage_youqi']            = $value['tonnage_youqi'];
                            $temp['pppayment.tonnage_rengong']          = $value['tonnage_rengong'];
                            $temp['pppayment.tonnage_maohan']           = $value['tonnage_maohan'];
                            $temp['pppayment.productioncompany']        = $value['productioncompany'];
                            $temp['pppayment.productionoverview']       = $value['productionoverview'];
                            $temp['pppayment.paymentdate']              = $value['paymentdate'];
                            $temp['pppayment.applicant']                = $value['applicant'];
                            $temp['pppayment.tonnage']                  = $value['tonnage'];
                            array_push($data, $temp);
                        }
                        $tonnagetotal_pppayment += $value['tonnage'];
                    }
//                dd($data);
                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $totalrowcolor = "#00FF00";       // green
                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase, $tonnagetotal_pppayment]);
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
        $filename = "下图申购结算明细报表";
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

                    $issuedrawings = $this->issuedrawingjson($request, 0, $factory);
                    $issuedrawingsArray = $issuedrawings->getData(true)["data"];
                    foreach ($issuedrawingsArray as $value)
                    {
                        $temp = [];
                        $temp['issuedrawing.created_date']          = $value['created_date'];
                        $temp['issuedrawing.tonnage']                = $value['tonnage'];
                        $temp['issuedrawing.applicant']              = $value['applicant'];
                        $temp['issuedrawing.productioncompany']     = $value['productioncompany'];
                        $temp['issuedrawing.overview']               = $value['overview'];

                        $temp['mcitempurchase.created_date']         = '';
                        $temp['mcitempurchase.manufacturingcenter'] = '';
                        $temp['mcitempurchase.totalweight']          = '';
                        $temp['mcitempurchase.detailuse']            = '';

                        $temp['pppayment.created_date']             = '';
                        $temp['pppayment.tonnage_paowan']           = '';
                        $temp['pppayment.tonnage_youqi']            = '';
                        $temp['pppayment.tonnage_rengong']          = '';
                        $temp['pppayment.tonnage_maohan']           = '';
                        $temp['pppayment.productioncompany']        = '';
                        $temp['pppayment.productionoverview']       = '';
                        $temp['pppayment.paymentdate']              = '';
                        $temp['pppayment.applicant']                = '';
                        $temp['pppayment.tonnage']                  = '';
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
                            $data[$key]['mcitempurchase.created_date']          = $value['created_date'];
                            $data[$key]['mcitempurchase.manufacturingcenter']  = $value['manufacturingcenter'];
                            $data[$key]['mcitempurchase.totalweight']           = $value['totalweight'];
                            $data[$key]['mcitempurchase.detailuse']             = $value['detailuse'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['issuedrawing.created_date']          = '';
                            $temp['issuedrawing.tonnage']                = '';
                            $temp['issuedrawing.applicant']              = '';
                            $temp['issuedrawing.productioncompany']     = '';
                            $temp['issuedrawing.overview']               = '';

                            $temp['mcitempurchase.created_date']         = $value['created_date'];
                            $temp['mcitempurchase.manufacturingcenter'] = $value['manufacturingcenter'];
                            $temp['mcitempurchase.totalweight']          = $value['totalweight'];
                            $temp['mcitempurchase.detailuse']            = $value['detailuse'];

                            $temp['pppayment.created_date']             = '';
                            $temp['pppayment.tonnage_paowan']           = '';
                            $temp['pppayment.tonnage_youqi']            = '';
                            $temp['pppayment.tonnage_rengong']          = '';
                            $temp['pppayment.tonnage_maohan']           = '';
                            $temp['pppayment.productioncompany']        = '';
                            $temp['pppayment.productionoverview']       = '';
                            $temp['pppayment.paymentdate']              = '';
                            $temp['pppayment.applicant']                = '';
                            $temp['pppayment.tonnage']                  = '';
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
                            $data[$key]['pppayment.created_date']           = $value['created_date'];
                            $data[$key]['pppayment.tonnage_paowan']         = $value['tonnage_paowan'];
                            $data[$key]['pppayment.tonnage_youqi']          = $value['tonnage_youqi'];
                            $data[$key]['pppayment.tonnage_rengong']        = $value['tonnage_rengong'];
                            $data[$key]['pppayment.tonnage_maohan']          = $value['tonnage_maohan'];
                            $data[$key]['pppayment.productioncompany']      = $value['productioncompany'];
                            $data[$key]['pppayment.productionoverview']     = $value['productionoverview'];
                            $data[$key]['pppayment.paymentdate']             = $value['paymentdate'];
                            $data[$key]['pppayment.applicant']               = $value['applicant'];
                            $data[$key]['pppayment.tonnage']                 = $value['tonnage'];
                        }
                        else
                        {
                            $temp = [];
                            $temp['issuedrawing.created_date']          = '';
                            $temp['issuedrawing.tonnage']                = '';
                            $temp['issuedrawing.applicant']              = '';
                            $temp['issuedrawing.productioncompany']     = '';
                            $temp['issuedrawing.overview']               = '';

                            $temp['mcitempurchase.created_date']         = '';
                            $temp['mcitempurchase.manufacturingcenter'] = '';
                            $temp['mcitempurchase.totalweight']          = '';
                            $temp['mcitempurchase.detailuse']            = '';

                            $temp['pppayment.created_date']             = $value['created_date'];
                            $temp['pppayment.tonnage_paowan']           = $value['tonnage_paowan'];
                            $temp['pppayment.tonnage_youqi']            = $value['tonnage_youqi'];
                            $temp['pppayment.tonnage_rengong']          = $value['tonnage_rengong'];
                            $temp['pppayment.tonnage_maohan']           = $value['tonnage_maohan'];
                            $temp['pppayment.productioncompany']        = $value['productioncompany'];
                            $temp['pppayment.productionoverview']       = $value['productionoverview'];
                            $temp['pppayment.paymentdate']              = $value['paymentdate'];
                            $temp['pppayment.applicant']                = $value['applicant'];
                            $temp['pppayment.tonnage']                  = $value['tonnage'];
                            array_push($data, $temp);
                        }
                        $tonnagetotal_pppayment += $value['tonnage'];
                    }
                    $sheet->freezeFirstRow();
                    $sheet->fromArray($data);

                    $totalrowcolor = "#00FF00";       // green
                    if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
                        $totalrowcolor = "#FF0000"; // red
                    $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase, $tonnagetotal_pppayment]);
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
}
