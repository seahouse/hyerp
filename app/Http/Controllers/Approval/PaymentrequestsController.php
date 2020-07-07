<?php

namespace App\Http\Controllers\Approval;

use App\Models\Approval\Vendordeduction;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\HelperController;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Paymentrequestattachment;
use App\Models\Purchase\Vendinfo_hxold;
use Auth, DB, Excel, PDF, Log;
use Dompdf\Dompdf;
use Jenssegers\Agent\Agent;
use App\Models\Product\Itemp_hxold;
use App\Models\Product\Itemp_hxold2;
use App\Models\Inventory\Receiptorder_hxold;
use App\Models\Inventory\Receiptitem_hxold;
use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\System\Employee_hxold_t;
use App\Models\Inventory\Rwrecord_hxold;
use Response;
use Datatables;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;

class PaymentrequestsController extends Controller
{
    private static $approvaltype_name = "供应商付款";
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // dd(Employee_hxold_t::all()->first());

        $request = request();
        $key = $request->input('key', '');
        $approvalstatus = $request->input('approvalstatus', '');
        $paymentstatus = $request->input('paymentstatus');
        $inputs = $request->all();
        $items = $this->searchrequest($request);
        $totalamount = $items->get()->sum('amount');
//        $totalamount = Paymentrequest::sum('amount');
        $paymentrequests = $items->paginate(10);
//        if (null !== request('key'))
//            $paymentrequests = $this->searchrequest($request)->paginate(10);
//        else
//            $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);
        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();

//        return view('approval.paymentrequests.index');

        // if ($request->has('key'))
        // use request('key') for null compare, not $request->has('key')

        if (null !== request('key'))        
        {
            return view('approval.paymentrequests.index', compact('paymentrequests', 'key', 'inputs', 'purchaseorders', 'totalamount'));
        }
        else
        {
            return view('approval.paymentrequests.index', compact('paymentrequests', 'purchaseorders', 'totalamount'));
        }
    }

    public function indexjson()
    {
        return Datatables::of(Paymentrequest::query())->make(true);

//        $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);
//        dd($paymentrequests);
//        $data = [
//            'draw' => 1,
//            'recordsTotal'  => 300,
//            'recordsFiltered'   =>300,
//        ];
//        return json_encode($data);
    }
    

    public function search(Request $request)
    {
        // dd(substr($request->header('referer'), strlen($request->header('origin'))));
        // dd($request->header('origin'));
        // dd($request->header('referer'));
        // dd($request->server('HTTP_REFERER'));

        // $key = $request->input('key');
        // $approvalstatus = $request->input('approvalstatus');

        // $supplier_ids = [];
        // $purchaseorder_ids = [];
        // if (strlen($key) > 0)
        // {
        //     $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
        //     $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');
        // }

        // $query = Paymentrequest::latest('created_at');

        // if (strlen($key) > 0)
        // {
        //     $query->whereIn('supplier_id', $supplier_ids)
        //         ->orWhereIn('pohead_id', $purchaseorder_ids);
        // }

        // if ($approvalstatus <> '')
        // {
        //     if ($approvalstatus == "1")
        //         $query->where('approversetting_id', '>', '0');
        //     else
        //         $query->where('approversetting_id', $approvalstatus);
        // }

        // $paymentrequests = $query->paginate(10);

        $key = $request->input('key');
        $approvalstatus = $request->input('approvalstatus');
        $paymentstatus = $request->input('paymentstatus');
        $inputs = $request->all();
        $items = $this->searchrequest($request);
        $totalamount = $items->get()->sum('amount');
//        $totalamount = Paymentrequest::sum('amount');
        $paymentrequests = $items->paginate(10);
        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
//        $totalamount = Paymentrequest::sum('amount');

        return view('approval.paymentrequests.index', compact('paymentrequests', 'key', 'inputs', 'purchaseorders', 'totalamount'));
    }

    // 手机端的搜索，仅搜索自己权限的数据
    // mindexmy：发起人搜索，搜索自己发起的审批单
    public function msearch(Request $request)
    {
        // $referer_url = substr($request->header('referer'), strlen($request->header('origin')));
        // dd($referer_url);
        // dd(substr($request->header('referer'), strlen($request->header('origin'))));
        // dd($request->header('origin'));
        // dd($request->header('referer'));
        // dd($request->server('HTTP_REFERER'));
        $key = $request->input('key');
        if ($key == '')
            return redirect('/approval/mindexmy');

        $paymentrequests = $this->search2($key);
        
        // dd($referer_url);
        // return view('approval.paymentrequests.index', compact('paymentrequests', 'key'));
        return view('approval.mindexmy', compact('paymentrequests', 'key'));
    }

    public function search2($key = '')
    {
        if ($key == '')
            return Paymentrequest::latest('created_at')->paginate(10);
        
        $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
        $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');

        $paymentrequests = Paymentrequest::latest('created_at')
            ->leftJoin('users', 'users.id', '=', 'paymentrequests.applicant_id')
            ->whereIn('supplier_id', $supplier_ids)
            // ->orWhere('amount', $key)
            ->orWhereIn('pohead_id', $purchaseorder_ids)
            ->orWhere('users.name', 'like', '%'.$key.'%')
            // ->leftJoin('sqls.vsupplier', 'vsupplier.id', '=', 'paymentrequest.supplier_id')
            // ->where('created_at', 'like', '%' . $key . '%')
            ->select('paymentrequests.*')
            ->paginate(10);
        // ->where('item_number', 'like', '%' . $key . '%')->orWhere('item_name', 'like', '%' . $key . '%')->paginate(10);

        return $paymentrequests;
    }

    public function searchrequest($request)
    {
        $key = $request->input('key');
        $approvalstatus = $request->input('approvalstatus');        
//        dd($key);
        //dd($request);
        $supplier_ids = [];
        $purchaseorder_ids = [];
        if (strlen($key) > 0)
        {
            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');
        }
        //dd($purchaseorder_ids);
        $query = Paymentrequest::latest('paymentrequests.created_at');

        if (strlen($key) > 0)
        {
            $query->where(function($query) use ($supplier_ids, $purchaseorder_ids, $key) {
                $query->whereIn('supplier_id', $supplier_ids)
                    ->orWhereIn('pohead_id', $purchaseorder_ids)
                    ->orWhere('descrip', 'like', '%'.$key.'%');     // 增加 说明 字段的模糊查找
            });
        }

        if ($approvalstatus <> '')
        {
            if ($approvalstatus == "1")
                $query->where('approversetting_id', '>', '0');
            else
                $query->where('approversetting_id', $approvalstatus);
        }

        if ($request->has('approvaldatestart') && $request->has('approvaldateend'))
        {
            if ($request->has('approver_id_date'))
            {
                $paymentrequestids = DB::table('paymentrequestapprovals')
                    ->where('approver_id', $request->input('approver_id_date'))
                    ->select('paymentrequest_id')
                    ->groupBy('paymentrequest_id')
                    ->havingRaw('max(paymentrequestapprovals.created_at) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'')
                    ->pluck('paymentrequest_id');
            }
            else
            {
                $paymentrequestids = DB::table('paymentrequestapprovals')
                    ->select('paymentrequest_id')
                    ->groupBy('paymentrequest_id')
                    ->havingRaw('max(paymentrequestapprovals.created_at) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'')
                    ->pluck('paymentrequest_id');
            }

            $query->whereIn('id', $paymentrequestids);

            // $query->leftJoin('paymentrequestapprovals', 'paymentrequestapprovals.paymentrequest_id', '=', 'paymentrequests.id')
            //     // ->select('paymentrequests.id', DB::raw('max(paymentrequestapprovals.created_at)'))
            //     ->groupBy('paymentrequests.id')
            //     ->havingRaw('max(paymentrequestapprovals.created_at1) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'::timestamp + interval \'1D\'');

            // $query->leftJoin('paymentrequestapprovals', function($join) use ($request) {
            //     $join->on('paymentrequestapprovals.paymentrequest_id', '=', 'paymentrequests.id')
            //         ->where('max(paymentrequestapprovals.created_at) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'::timestamp + interval \'1D\'');

            // });

            // ->groupBy('paymentrequests.id')
            //         ->havingRaw('max(paymentrequestapprovals.created_at) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'::timestamp + interval \'1D\'');
                // ->select('paymentrequests.id', DB::raw('max(paymentrequestapprovals.created_at)'))

                

        }

        // paymentmethod
        if ($request->has('paymentmethod'))
        {
            $query->where('paymentmethod', $request->input('paymentmethod'));
        }

        // payment status
        // because need search hxold database, so select this condition last.
        if ($request->has('paymentstatus'))
        {
            $paymentstatus = $request->input('paymentstatus');
            if ($paymentstatus == 0)
            {
                $query->where('approversetting_id', '0');
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') < $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//                $query->whereIn('id', $paymentrequestids);
                $query->whereRaw('(select MAX(created_at) from paymentrequestapprovals where paymentrequestapprovals.paymentrequest_id=paymentrequests.id)<(select MAX(create_date) from hxcrm2016..vpayments where vpayments.pohead_id=paymentrequests.pohead_id)');

                // $query->whereHas('paymentrequestapprovals', function($query) {
                //     $query->from('sqlsrv.vpayments')->whereRaw('max(create_date) > max(paymentrequestapprovals.created_at)');
                // });

                // $query->leftJoin('paymentrequestapprovals', 'paymentrequestapprovals.paymentrequest_id', '=', 'paymentrequests.id')
                //     ->leftJoin(DB::connection('sqlsrv')->table('vpurchaseorder'), 'vpurchaseorder.id', '=', 'paymentrequests.pohead_id')
                //     ->select('paymentrequests.id', DB::raw('max(paymentrequestapprovals.created_at)'))
                //     ->groupBy('paymentrequests.id')
                //     ->havingRaw('max(paymentrequestapprovals.created_at) < now()');
            }
            elseif ($paymentstatus == -1)
            {
                $query->where('approversetting_id', '0');
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') > $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//                $query->whereIn('id', $paymentrequestids);
                $query->whereRaw('(select MAX(created_at) from paymentrequestapprovals where paymentrequestapprovals.paymentrequest_id=paymentrequests.id)>(select isnull(MAX(create_date),\'1900-01-01\') from hxcrm2016..vpayments where vpayments.pohead_id=paymentrequests.pohead_id)');
            }
        }
        if ($request->has('company_id'))
        {
            $query->leftJoin('hxcrm2016.dbo.vpurchaseorder', 'hxcrm2016.dbo.vpurchaseorder.id', '=', 'paymentrequests.pohead_id');
            $query->where('purchasecompany_id', $request->input('company_id'));
        }

        $paymentrequests = $query->select('paymentrequests.*');
        // dd($paymentrequests->pluck('pohead_id'));

        // $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        // dd($purchaseorders->pluck('id'));

        return $paymentrequests;
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

    /**
     * 我发起的数据集合.
     *
     * @return \Illuminate\Http\Response
     */
    public static function my(Request $request)
    {
        $userid = Auth::user()->id;

        $key = $request->input('key');
        $paymenttype = $request->input('paymenttype');
        $projectname = $request->input('projectname');
        $productname = $request->input('productname');
        $suppliername = $request->input('suppliername');

        $query = Paymentrequest::latest('created_at');
        $query->where('applicant_id', $userid);

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
        return $paymentrequests;


//        if ('' == $key)
//            return Paymentrequest::latest('created_at')
//                ->where('applicant_id', $userid)->paginate(10);
//
//        $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//        $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
//            ->where('descrip', 'like', '%'.$key.'%')
//            ->orWhere('productname', 'like', '%'.$key.'%')
//            ->pluck('id');
//
//        $paymentrequests = Paymentrequest::latest('created_at')
//            ->where('applicant_id', $userid)
//            ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
//                $query->whereIn('supplier_id', $supplier_ids)
//                    ->orWhereIn('pohead_id', $purchaseorder_ids);
//            })
//            ->select('paymentrequests.*')
//            ->paginate(10);
//
//        return $paymentrequests;
    }

    public static function mying($key = '')
    {
        $userid = Auth::user()->id;

        if ('' == $key)
            return Paymentrequest::latest('created_at')
                ->where('applicant_id', $userid)
                ->where('approversetting_id', '>', 0)->paginate(10);

        $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
        $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');

        $paymentrequests = Paymentrequest::latest('created_at')
            ->where('applicant_id', $userid)
            ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
                $query->whereIn('supplier_id', $supplier_ids)
                    ->orWhereIn('pohead_id', $purchaseorder_ids);
            })
            ->select('paymentrequests.*')
            ->paginate(10);

        // $paymentrequests = Paymentrequest::latest('created_at')->where('applicant_id', $userid)->paginate(10);

        return $paymentrequests;
    }

    /**
     * 我发起的数据集合.
     *
     * @return \Illuminate\Http\Response
     */
    public static function myed($key = '')
    {
        $userid = Auth::user()->id;

        if ('' == $key)
            return Paymentrequest::latest('created_at')
                ->where('applicant_id', $userid)
                ->where('approversetting_id', '<=', '0')->paginate(10);

        $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
        $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');

        $paymentrequests = Paymentrequest::latest('created_at')
            ->where('applicant_id', $userid)
            ->where(function ($query) use ($supplier_ids, $purchaseorder_ids) {
                $query->whereIn('supplier_id', $supplier_ids)
                    ->orWhereIn('pohead_id', $purchaseorder_ids);
            })
            ->select('paymentrequests.*')
            ->paginate(10);

        // $paymentrequests = Paymentrequest::latest('created_at')->where('applicant_id', $userid)->paginate(10);

        return $paymentrequests;
    }

    /**
     * 待我审批的报销单
     *
     * @return \Illuminate\Http\Response
     */
    public static function myapproval()
    {
        $approvaltype_id = self::typeid();

        // 登录人在审批流程中的位置
        $userid = Auth::user()->id;
        $approversettings = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->get();
        $approversetting_id_my = 0;
        $approversetting_level = 0;
        foreach ($approversettings as $approversetting) {
            // 如果已设置了审批人，则使用审批人，否则使用部门/职位
            if ($approversetting->approver_id > 0)
            {
                if ($approversetting->approver_id == $userid)
                {
                    $approversetting_id_my = $approversetting->id;
                    $approversetting_level = $approversetting->level;
                    break;
                }
            }
            else
            {
                if ($approversetting->dept_id > 0 && strlen($approversetting->position) > 0)    // 设置了部门与职位才进行查找
                {
                    $user = User::where('dept_id', $approversetting->dept_id)->where('position', $approversetting->position)->first();
                    if ($user->id == $userid)
                    {
                        $approversetting_id_my = $approversetting->id;
                        $approversetting_level = $approversetting->level;
                        break;
                    }            
                }
            }            

        }
        
        // 如果当前操作人员在审批流程中
        // 先随意查询一个结果给$paymentrequests赋值
        $paymentrequests = Paymentrequest::where('id', -1)->paginate(10);
        if ($approversetting_id_my > 0)
        {           
            $paymentrequests = Paymentrequest::latest('created_at')->where('approversetting_id', $approversetting_id_my)->paginate(10);
            // $paymentrequests = DB::table('paymentrequests')->where('approversetting_id', $approversetting_id_my)->latest('created_at')->get();
        }

        // dd($paymentrequests);
        return $paymentrequests;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mcreate()
    {
        //
//        $vendordecution=DB::table('vendordeductions')
//            ->leftJoin('vendordeductionitems', 'vendordeductions.id', '=', 'vendordeductionitems.vendordeduction_id')
//            ->where('vendordeductions.status','>=',0)
//            ->where('vendordeductions.pohead_id','=',29496)
//            ->select(DB::raw('sum(vendordeductionitems.quantity * vendordeductionitems.unitprice) as decutionamount'))->first();
//        if (isset($vendordecution))
//            dd($vendordecution->decutionamount);
//        dd($vendordecution->decutionamount);
        $config = DingTalkController::getconfig();
        $agent = new Agent();

        return view('approval/paymentrequests/mcreate', compact('config', 'agent'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mstore(Request $request)
    {
        //
        $input = $request->all();        
        $input = HelperController::skipEmptyValue($input);
        // dd($input);
        // dd($request->hasFile('paymentnodeattachments'));
        // dd($request->file('paymentnodeattachments'));
        // dd($request->file('paymentnodeattachments')->getClientOriginalExtension());
        // dd($request->input('amount', '0.0'));

        // $files = array_get($input,'paymentnodeattachments');
        // $destinationPath = 'uploads';
        // foreach ($files as $key => $file) {
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName = $file->getClientOriginalName() . '.' . $extension;
        //     // dd($file->getClientOriginalName());
        //     $upload_success = $file->move($destinationPath, $fileName);
        // }

        $needGeneratePdf = false;
        if ($request->has('paymenttype') && $request->input('paymenttype') === '到货款' && !$request->hasFile('paymentnodeattachments'))
        {
            // 如果已经全部到货，且到货地等于'无锡工厂'， 且"货到"的付款百分比不等于0
            $pohead = Purchaseorder_hxold::where('id', $request->input('pohead_id'))->first();
//            dd($pohead);
            if (isset($pohead) && $pohead->arrival_percent >= 0.99 && $pohead->arrival === '无锡工厂' && floatval($pohead->arrival_pay_percent) > 0.0)
            {
                $needGeneratePdf = true;
            }
            else
                dd("到货款必须要上传付款节点审批单。");
        }



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


        $paymentrequest = Paymentrequest::create($input);

        // auto generate paymentnodeattachments (pdf)
        if ($paymentrequest && $needGeneratePdf)
        {
            $str = '<html>';
            $str .= '<head>';
            $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
            $str .= '</head>';
            $str .= '<body>';

            $str .= '<h1 style="font-family: DroidSansFallback; text-align:center">供应商到货款节点' . '</h1>';

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
            $str .= '<td style="font-family: DroidSansFallback;">货到目的类型</td>';
            $str .= '<td style="font-family: DroidSansFallback;">' . '发仓库 - ' . (isset($paymentrequest->purchaseorder_hxold->receiptorders->first()->rwrecord->handler->name) ? $paymentrequest->purchaseorder_hxold->receiptorders->first()->rwrecord->handler->name : '') . '</td>';
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

            $str .= '</tbody></table>';

//            $str .= '<p style="font-family: DroidSansFallback;">申请人: ' . $paymentrequest->applicant->name . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">货到目的类型: ' . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">供应商: ' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">采购商品名称: ' . (isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '') . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">对应工程名称: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->descrip) ? $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">项目所属销售经理: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->salesmanager) ? $paymentrequest->purchaseorder_hxold->sohead->salesmanager : '') . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">工程类型: ' . '</p>';
//            $str .= '<p style="font-family: DroidSansFallback;">采购合同: ' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</p>';

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
            $paymentnodeattachment->filename = '供应商到货款节点(自动生成)';
            $paymentnodeattachment->path = "/$dest";     // add a '/' in the head.
            $paymentnodeattachment->save();
        }

        // create paymentnodeattachments
        if ($paymentrequest)
        {
            $files = array_get($input,'paymentnodeattachments');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            foreach ($files as $key => $file) {
                if ($file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $paymentnodeattachment = new Paymentrequestattachment;
                    $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                    $paymentnodeattachment->type = "paymentnode";
                    $paymentnodeattachment->filename = $file->getClientOriginalName();
                    $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $paymentnodeattachment->save();
                }
            }
        }

        // create businesscontractattachments
        if ($paymentrequest)
        {
            $files = array_get($input,'businesscontractattachments');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            foreach ($files as $key => $file) {
                if ($file)
                {
                    $extension = $file->getClientOriginalExtension();
                    $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $paymentnodeattachment = new Paymentrequestattachment;
                    $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                    $paymentnodeattachment->type = "businesscontract";
                    $paymentnodeattachment->filename = $file->getClientOriginalName();
                    $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $paymentnodeattachment->save();
                }

            }
        }

        // create images in the desktop
        if ($paymentrequest)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $paymentnodeattachment = new Paymentrequestattachment;
                        $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
                        $paymentnodeattachment->type = "image";
                        $paymentnodeattachment->filename = $file->getClientOriginalName();
                        $paymentnodeattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $paymentnodeattachment->save();
                    }

                }
            }

        }

        // create reimbursement images
        if ($paymentrequest)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            foreach ($images as $key => $value) {
                # code...
                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/paymentrequest/' . $paymentrequest->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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

                file_put_contents("$dir/$filename", file_get_contents($value));
                // file_put_contents('abcd.jpg', file_get_contents($value));

                // response()->download($value);
                // Storage::put('abcde.jpg', file_get_contents($value));
                // copy(storage_path('app') . '/' . $sFilename, '/images/' . $sFilename);

                // add image record
                $paymentrequestattachment = new Paymentrequestattachment;
                $paymentrequestattachment->paymentrequest_id = $paymentrequest->id;
                $paymentrequestattachment->type = "image";     // add a '/' in the head.
                $paymentrequestattachment->path = "/$dir$filename";     // add a '/' in the head.
                $paymentrequestattachment->save();
            }
        }

        if ($paymentrequest)
        {
            // send dingtalk message.
            $touser = $paymentrequest->nextapprover();
            if ($touser)
            {
                // DingTalkController::send($touser->dtuserid, '', 
                //     '来自' . $paymentrequest->applicant->name . '的付款单需要您审批.', 
                //     config('custom.dingtalk.agentidlist.approval'));     

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

//                DingTalkController::send_link($touser->dtuserid, '',
//                    url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
//                    '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.',
//                    config('custom.dingtalk.agentidlist.approval'));

//                if (Auth::user()->email == "admin@admin.com")
//                {
//                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
//                        url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
//                        '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', $paymentrequest,
//                        config('custom.dingtalk.agentidlist.approval'));
//                }

            }

        }

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
        $paymentrequest = Paymentrequest::findOrFail($id);
        $agent = new Agent();
        $config = DingTalkController::getconfig();
        return view('approval.paymentrequests.show', compact('paymentrequest', 'agent', 'config'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mshow($id)
    {
        //
        $paymentrequest = Paymentrequest::findOrFail($id);
        $agent = new Agent();
        $config = DingTalkController::getconfig();
        return view('approval.paymentrequests.mshow', compact('paymentrequest', 'agent', 'config'));
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
        Paymentrequest::destroy($id);
        return redirect('/approval/paymentrequests');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mdestroy($id)
    {
        //
        Paymentrequest::destroy($id);
        return redirect('/approval/mindexmy');
    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        //
        // Excel::create('test1111')->export('xlsx');

        Excel::create('test1111', function($excel) {
            $excel->sheet('Sheetname', function($sheet) {

                // Sheet manipulation
                $paymentrequests = $this->search2()->toArray();
//                dd($this->search2());
                dd($paymentrequests["data"]);
                $sheet->fromArray($paymentrequests["data"]);
            });

            // Set the title
            $excel->setTitle('Our new awesome title');

            // Chain the setters
            $excel->setCreator('Maatwebsite')
                  ->setCompany('Maatwebsite');

            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');

        })->export('xls');

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

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportitem($id)
    {
        //
        $paymentrequest = Paymentrequest::findOrFail($id);

        ////////////////////////////////////////////////////
        $str = '<html>';
        $str .= '<head>';
        $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $str .= '</head>';
        $str .= '<body>';

//        $str .= '<h1 style="font-family: DroidSansFallback; text-align:center">供应商到货款节点' . '</h1>';
//
//        $str .= '<table border="1px" cellpadding="0" cellspacing="0" width="100%"><tbody>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">申请人</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . $paymentrequest->applicant->name . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">申请人部门</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . $paymentrequest->applicant->dept->name . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">货到目的类型</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . '发仓库 - ' . (isset($paymentrequest->purchaseorder_hxold->receiptorders->first()->rwrecord->handler->name) ? $paymentrequest->purchaseorder_hxold->receiptorders->first()->rwrecord->handler->name : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">供应商</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">采购商品名称</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">对应工程名称</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' .  (isset($paymentrequest->purchaseorder_hxold->sohead->descrip) ? $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">项目所属销售经理</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->sohead->salesmanager) ? $paymentrequest->purchaseorder_hxold->sohead->salesmanager : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">工程类型</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->sohead->equipmenttype) ? $paymentrequest->purchaseorder_hxold->sohead->equipmenttype : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">采购合同</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '<tr>';
//        $str .= '<td style="font-family: DroidSansFallback;">到货地</td>';
//        $str .= '<td style="font-family: DroidSansFallback;">' . (isset($paymentrequest->purchaseorder_hxold->arrival) ? $paymentrequest->purchaseorder_hxold->arrival : '') . '</td>';
//        $str .= '</tr>';
//
//        $str .= '</tbody></table>';

//        $str .= '<p style="font-family: DroidSansFallback;">申请人: ' . $paymentrequest->applicant->name . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">货到目的类型: ' . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">供应商: ' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">采购商品名称: ' . (isset($paymentrequest->purchaseorder_hxold->productname) ? $paymentrequest->purchaseorder_hxold->productname : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">对应工程名称: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->descrip) ? $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">项目所属销售经理: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->salesmanager) ? $paymentrequest->purchaseorder_hxold->sohead->salesmanager : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">工程类型: ' . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">采购合同: ' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</p>';

//        $str .= '<p style="font-family: DroidSansFallback;">供应商类型: ' . $paymentrequest->suppliertype . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款类型: ' . $paymentrequest->paymenttype . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">支付对象: ' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">采购合同: ' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">对应工程名称: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name) ? $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">合同金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount) ? $paymentrequest->purchaseorder_hxold->amount : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">已付金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_paid) ? $paymentrequest->purchaseorder_hxold->amount_paid : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">已开票金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_ticketed) ? $paymentrequest->purchaseorder_hxold->amount_ticketed : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">到货情况: ' . $pohead_arrived . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . (isset($paymentrequest->purchaseorder_hxold->paymethod) ? $paymentrequest->purchaseorder_hxold->paymethod : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">安装完毕日期: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate) ? $paymentrequest->purchaseorder_hxold->sohead->installeddate : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">应付款设备名称: ' . $paymentrequest->equipmentname . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">说明: ' . $paymentrequest->descrip . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">本次请款额: ' . $paymentrequest->amount . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . $paymentrequest->paymentmethod . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">开户行: ' . (isset($paymentrequest->vendbank_hxold->bankname) ? $paymentrequest->vendbank_hxold->bankname : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">银行账号: ' . (isset($paymentrequest->vendbank_hxold->accountnum) ? $paymentrequest->vendbank_hxold->accountnum : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">审批记录:</p>';
//
//        foreach ($paymentrequest->paymentrequestapprovals as $paymentrequestapproval) {
//            $str .= '<p style="font-family: DroidSansFallback; text-indent:2em">审批人: ' . $paymentrequestapproval->approver->name . ', 审批结果: ' . ($paymentrequestapproval->status==0 ? '通过' : '未通过') . ', 审批时间: ' . $paymentrequestapproval->created_at . ', 审批描述: ' . $paymentrequestapproval->description . '</p>';
//
//        }

//        $str .= '</body>';
//        $str .= '</html>';
//
//        // instantiate and use the dompdf class
//        $dompdf = new Dompdf();
//        // $dompdf->set_option('isFontSubsettingEnabled', true);
//        $dompdf->loadHtml($str);
//
//        // (Optional) Setup the paper size and orientation
//        // $dompdf->setPaper('A4', 'landscape');
//        $dompdf->setPaper('A4');
//
//        // Render the HTML as PDF
//        $dompdf->render();
////        $dest = 'uploads/approval/paymentrequest/' . $paymentrequest->id . '/' . date('YmdHis').rand(100, 200) . '.pdf';
////        file_put_contents($dest, $dompdf->output());
//
//        // Output the generated PDF to Browser
//        $file = $dompdf->stream('供应商到货款节点');
////        dd($dompdf->output());
//
//        // add database record
//        $paymentnodeattachment = new Paymentrequestattachment;
//        $paymentnodeattachment->paymentrequest_id = $paymentrequest->id;
//        $paymentnodeattachment->type = "paymentnode";
//        $paymentnodeattachment->filename = '供应商到货款节点';
//        $paymentnodeattachment->path = "/$dest";     // add a '/' in the head.
//        $paymentnodeattachment->save();
//
//        return;

//         $str = '<html>
// <head>
//     <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
// </head>
// <body>
//     <p style="font-family: DroidSansFallback;">献给母亲的爱</p>
// </body>
// </html>';

        $pohead_arrived = '';
        if (isset($paymentrequest->purchaseorder_hxold->arrival_percent))
        {
            if ($paymentrequest->purchaseorder_hxold->arrival_percent <= 0.0)
                $pohead_arrived = '未到货';
            elseif ($paymentrequest->purchaseorder_hxold->arrival_percent > 0.0 && $paymentrequest->purchaseorder_hxold->arrival_percent < 0.99) 
                $pohead_arrived = '部分到货';
            else
                $pohead_arrived = '全部到货';
        }

        $str = '<html>';
        $str .= '<head>';
        $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $str .= '</head>';
        $str .= '<body>';


        $str .= '<p style="font-family: DroidSansFallback;">供应商类型: ' . $paymentrequest->suppliertype . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款类型: ' . $paymentrequest->paymenttype . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">支付对象: ' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">采购合同: ' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">对应工程名称: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name) ? $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">合同金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount) ? $paymentrequest->purchaseorder_hxold->amount : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">已付金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_paid) ? $paymentrequest->purchaseorder_hxold->amount_paid : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">已开票金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_ticketed) ? $paymentrequest->purchaseorder_hxold->amount_ticketed : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">到货情况: ' . $pohead_arrived . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . (isset($paymentrequest->purchaseorder_hxold->paymethod) ? $paymentrequest->purchaseorder_hxold->paymethod : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">安装完毕日期: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate) ? $paymentrequest->purchaseorder_hxold->sohead->installeddate : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">应付款设备名称: ' . $paymentrequest->equipmentname . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">说明: ' . $paymentrequest->descrip . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">本次请款额: ' . $paymentrequest->amount . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . $paymentrequest->paymentmethod . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">开户行: ' . (isset($paymentrequest->vendbank_hxold->bankname) ? $paymentrequest->vendbank_hxold->bankname : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">银行账号: ' . (isset($paymentrequest->vendbank_hxold->accountnum) ? $paymentrequest->vendbank_hxold->accountnum : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款方式2: ' . $paymentrequest->paymentmethod2 . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">开户行2: ' . (isset($paymentrequest->vendbank_hxold2->bankname) ? $paymentrequest->vendbank_hxold2->bankname : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">银行账号2: ' . (isset($paymentrequest->vendbank_hxold2->accountnum) ? $paymentrequest->vendbank_hxold2->accountnum : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">审批记录:</p>';

        foreach ($paymentrequest->paymentrequestapprovals as $paymentrequestapproval) {
            $str .= '<p style="font-family: DroidSansFallback; text-indent:2em">审批人: ' . $paymentrequestapproval->approver->name . ', 审批结果: ' . ($paymentrequestapproval->status==0 ? '通过' : '未通过') . ', 审批时间: ' . $paymentrequestapproval->created_at . ', 审批描述: ' . $paymentrequestapproval->description . '</p>';
            
        }

        $str .= '</body>';
        $str .= '</html>';

    



        // $str .= "<body>供应商类型: " . "aaa</body>";
        // dd($str);

        // // $agent = new Agent();
        // $paymentrequests = $this->search2()->toArray();
        // $pdf = PDF::loadView('approval.paymentrequests.index', $paymentrequests["data"]);
        // return $pdf->download('invoice.pdf');

        // $mpdf = new mpdf();
        // $mpdf->WriteHTML($str);
        // $mpdf->Output();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->loadHtml($str);

        // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');
        $dompdf->setPaper('A4');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($paymentrequest->id . '_' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '_' . $paymentrequest->amount);
        // $dompdf->stream("sample.pdf", array("Attachment" => true));

        // return 'ssss';
    }

    public function mrecvdetail($id)
    {
        //
        $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;
        // dd($purchaseorder);

        return view('approval.paymentrequests.mrecvdetail', compact('purchaseorder'));
    }

    public function mrecvdetail2($id)
    {
        //
        $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

        $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers2 = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number2');
        $itemps = Itemp_hxold2::whereIn('goods_no', $item_numbers2)->get();
        // dd($itemps);

        return view('approval.paymentrequests.mrecvdetail2', compact('purchaseorder', 'itemps'));
    }

    public function mrecvdetail3($id)
    {
        //
        $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

// select * from vgoods
// where goods_no
// in
// (
// select distinct item_number from vreceiptitem
// where receipt_id in 
// (
// select receipt_id from vreceiptorder
// where pohead_id=15984
// )
// )
        $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number');
        // dd($item_numbers);
        $itemps = Itemp_hxold::whereIn('goods_no', $item_numbers)->get();
        // dd($itemps);

        return view('approval.paymentrequests.mrecvdetail3', compact('purchaseorder', 'itemps'));
    }

    public function mrecvdetail4($id)
    {
        $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

        $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers2 = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number2');
        $itemps2 = Itemp_hxold2::whereIn('goods_no', $item_numbers2)->get();

        // return view('approval.paymentrequests.mrecvdetail2', compact('purchaseorder', 'itemps'));

        // $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

// select * from vgoods
// where goods_no
// in
// (
// select distinct item_number from vreceiptitem
// where receipt_id in 
// (
// select receipt_id from vreceiptorder
// where pohead_id=15984
// )
// )
        // $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number');
        $itemps = Itemp_hxold::whereIn('goods_no', $item_numbers)->get();

        return view('approval.paymentrequests.mrecvdetail4', compact('purchaseorder', 'itemps2', 'itemps'));
    }

    public function mrecvdetail5($id)
    {
        $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

        $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers2 = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number2');
        $itemps2 = Itemp_hxold2::whereIn('goods_no', $item_numbers2)->get();

        // return view('approval.paymentrequests.mrecvdetail2', compact('purchaseorder', 'itemps'));

        // $purchaseorder = Paymentrequest::findOrFail($id)->purchaseorder_hxold;

        // $receipt_ids = Receiptorder_hxold::where('pohead_id', $purchaseorder->id)->pluck('receipt_id');
        $item_numbers = Receiptitem_hxold::whereIn('receipt_id', $receipt_ids)->distinct()->pluck('item_number');
        $itemps = Itemp_hxold::whereIn('goods_no', $item_numbers)->get();

        $receiptid = 0;
        foreach ($receipt_ids as $receipt_id) {
            $receiptid = $receipt_id;
            break;
        }
        return view('approval.paymentrequests.mrecvdetail5', compact('purchaseorder', 'itemps2', 'itemps', 'receiptid'));
    }

    public function mrecvdetail5data($itemid, $receiptid = 0)
    {
        $item = Itemp_hxold::where('goods_id', $itemid)->firstOrFail();
        $receiptitems = Receiptitem_hxold::where('item_number', $item->goods_no)
            ->leftJoin('vgoods', 'vgoods.goods_no', '=', 'vreceiptitem.item_number')
            ->leftJoin('vrwrecord', 'vrwrecord.id', '=', 'vreceiptitem.receipt_id')
            ->leftJoin('vwarehouse', 'vwarehouse.number', '=', 'vrwrecord.warehouse_number')
            ->leftJoin('vsupplier', 'vsupplier.id', '=', 'vrwrecord.supplier_id')
            ->leftJoin('vreceiptorder', 'vreceiptorder.receipt_id', '=', 'vrwrecord.id')
            ->leftJoin('vpurchaseorder', 'vpurchaseorder.id', '=', 'vreceiptorder.pohead_id')
            ->leftJoin('vorder', 'vorder.id', '=', 'vpurchaseorder.sohead_id')->select([
                'vreceiptitem.quantity',
                Db::raw('convert(decimal(18,3), vreceiptitem.unitprice * (1+taxrate/100.0)) as unitprice'),
                'vgoods.goods_unit_name',
                Db::raw('convert(decimal(18,3), vreceiptitem.amount * (1+taxrate/100.0)) as price'),
                'vreceiptitem.material',
                'vreceiptitem.size',
                'vwarehouse.name',
                'vsupplier.name as supplier_name',
                Db::raw('convert(varchar(100), vpurchaseorder.orderdate, 23) as purchaseorder_orderdate'),
                DB::raw("case vorder.projectjc when '' then vorder.descrip else vorder.projectjc end as order_projectjc"),
                'vreceiptitem.out_sohead_name',
                Db::raw('convert(varchar(100), vreceiptitem.record_at, 23) as receiptitem_record_at'),
                'vreceiptitem.receipt_id',
            ]);
//        Log::info($receiptitems->count);
        return Datatables::of($receiptitems)
            ->setRowClass(function ($receiptitem) use ($receiptid) {
                return $receiptitem->receipt_id == $receiptid ? 'success' : '';
            })->make();

        return view('approval.paymentrequests.mrecvdetail5', compact('purchaseorder', 'itemps2', 'itemps'));
    }

    public function printpage($id)
    {
        $paymentrequest = Paymentrequest::findOrFail($id);
        $agent = new Agent();
        $config = DingTalkController::getconfig();
        return view('approval.paymentrequests.show_print', compact('paymentrequest', 'agent', 'config'));
    }

    public function pay($id)
    {
        $paymentrequest = Paymentrequest::findOrFail($id);
        if ($paymentrequest->approversetting_id == 0)
        {
            if (isset($paymentrequest->purchaseorder_hxold->payments))
            {
                if ($paymentrequest->paymentrequestapprovals->max('created_at') > $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
                {
                    return redirect('/purchase/purchaseorders/' . $paymentrequest->pohead_id . '/payments/create_hxold/' . $paymentrequest->amount);
                }
            }
        }

        echo '无法付款：需要审批已完成，且此采购订单在审批完成后没有付款记录才可付款。如有问题，请联系管理员。';
    }

    // 对到货100%的采购订单进行创建审批单
    public static function createApprovalByArrival()
    {
        Log::info("createApprovalByArrival start...");
        return;
        //
//        $paymentrequest = Paymentrequest::findOrFail($id);
//
//
//        $pohead_arrived = '';
//        if (isset($paymentrequest->purchaseorder_hxold->arrival_percent))
//        {
//            if ($paymentrequest->purchaseorder_hxold->arrival_percent <= 0.0)
//                $pohead_arrived = '未到货';
//            elseif ($paymentrequest->purchaseorder_hxold->arrival_percent > 0.0 && $paymentrequest->purchaseorder_hxold->arrival_percent < 0.99)
//                $pohead_arrived = '部分到货';
//            else
//                $pohead_arrived = '全部到货';
//        }

        $str = '<html>';
        $str .= '<head>';
        $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $str .= '</head>';
        $str .= '<body>';

        $str .= '<h1 style="font-family: DroidSansFallback;">供应商到货款节点' . '</h1>';

//        $str .= '<p style="font-family: DroidSansFallback;">供应商类型: ' . $paymentrequest->suppliertype . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款类型: ' . $paymentrequest->paymenttype . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">支付对象: ' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">采购合同: ' . (isset($paymentrequest->purchaseorder_hxold->number) ? $paymentrequest->purchaseorder_hxold->number : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">对应工程名称: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name) ? $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . $paymentrequest->purchaseorder_hxold->sohead->descrip : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">合同金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount) ? $paymentrequest->purchaseorder_hxold->amount : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">已付金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_paid) ? $paymentrequest->purchaseorder_hxold->amount_paid : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">已开票金额: ' . (isset($paymentrequest->purchaseorder_hxold->amount_ticketed) ? $paymentrequest->purchaseorder_hxold->amount_ticketed : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">到货情况: ' . $pohead_arrived . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . (isset($paymentrequest->purchaseorder_hxold->paymethod) ? $paymentrequest->purchaseorder_hxold->paymethod : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">安装完毕日期: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate) ? $paymentrequest->purchaseorder_hxold->sohead->installeddate : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">应付款设备名称: ' . $paymentrequest->equipmentname . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">说明: ' . $paymentrequest->descrip . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">本次请款额: ' . $paymentrequest->amount . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . $paymentrequest->paymentmethod . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">开户行: ' . (isset($paymentrequest->vendbank_hxold->bankname) ? $paymentrequest->vendbank_hxold->bankname : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">银行账号: ' . (isset($paymentrequest->vendbank_hxold->accountnum) ? $paymentrequest->vendbank_hxold->accountnum : '') . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
//        $str .= '<p style="font-family: DroidSansFallback;">审批记录:</p>';
//
//        foreach ($paymentrequest->paymentrequestapprovals as $paymentrequestapproval) {
//            $str .= '<p style="font-family: DroidSansFallback; text-indent:2em">审批人: ' . $paymentrequestapproval->approver->name . ', 审批结果: ' . ($paymentrequestapproval->status==0 ? '通过' : '未通过') . ', 审批时间: ' . $paymentrequestapproval->created_at . ', 审批描述: ' . $paymentrequestapproval->description . '</p>';
//
//        }

        $str .= '</body>';
        $str .= '</html>';






        // // $agent = new Agent();
        // $paymentrequests = $this->search2()->toArray();
        // $pdf = PDF::loadView('approval.paymentrequests.index', $paymentrequests["data"]);
        // return $pdf->download('invoice.pdf');

        // $mpdf = new mpdf();
        // $mpdf->WriteHTML($str);
        // $mpdf->Output();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->loadHtml($str);

        // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');
        $dompdf->setPaper('A4');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('供应商到货款节点');
//        $dompdf->stream($paymentrequest->id . '_' . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : '') . '_' . $paymentrequest->amount);

        return;

        Log::info('createApprovalByArrival start');

        // 获取今天的入库单信息
        $rwrecords = Rwrecord_hxold::where('create_at', '>=', '2017-05-24')->get();
        Log::info('$rwrecords->count(): ' . $rwrecords->count());
        foreach ($rwrecords as $rwrecord)
        {
            Log::info('$rwrecord->id: ' . $rwrecord->id);
            Log::info('$rwrecord->receiptorder->pohead_id: ' . $rwrecord->receiptorder->pohead_id);
            $pohead = $rwrecord->receiptorder->pohead;
            if (isset($rwrecord->receiptorder->pohead->arrival_percent))
            {
                Log::info('$rwrecord->receiptorder->pohead->arrival_percent: ' . $rwrecord->receiptorder->pohead->arrival_percent);

                // 如果已经全部到货，且到货地等于'无锡工厂'， 且"货到"的付款百分比不等于0
                // 且不存在此审批单，进行创建
                Log::info('$pohead->arrival_pay_percent: ' . $pohead->arrival_pay_percent);
                if ($rwrecord->receiptorder->pohead->arrival_percent >= 0.99 && $rwrecord->receiptorder->pohead->arrival === '无锡工厂' && floatval($pohead->arrival_pay_percent) > 0.0)
                {
                    $paymentrequest = Paymentrequest::where('pohead_id', $rwrecord->receiptorder->pohead_id)
                        ->where('paymenttype', '到货款')->first();
                    if (isset($paymentrequest))
                    {
                        Log::info('Paymentrequest has exists.');
                    }
                    else
                    {
                        $applicant =  User::where('email', 'liuhuaming@huaxing-east.com')->first();
                        $approversetting_id = -1;
                        $approvaltype_id = self::typeid();
                        if ($approvaltype_id > 0)
                        {
                            $approversettingFirst = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->first();
                            if ($approversettingFirst)
                                $approversetting_id = $approversettingFirst->id;
                        }

                        $data = [
                            'supplier_id' => $pohead->vendinfo_id,
                            'pohead_id' => $pohead->id,
                            'amount'    => $pohead->amount * doubleval($pohead->arrival_pay_percent) / 100,
                            'paymentmethod' => '汇票',
                            'datepay'   => Carbon::now(),
                            'applicant_id'  => isset($applicant) ? $applicant->id : 0,
                            'approversetting_id'    => $approversetting_id,
                            'suppliertype'          => '机务设备类',
                            'paymenttype'           => '到货款',
                            'vendbank_id'           => $pohead->vendinfo->vendbank_id
                        ];
                        Paymentrequest::create($data);
                        Log::info('Create Paymentrequest Approval.');
                    }
                }
            }
        }
//        dd(Carbon::today());
    }

    // 判断是否有重复提交：同一个采购订单，同一个金额，在10天内是否有重复
    public function hasrepeat($pohead_id, $amount = 0)
    {
        $data = [
            "code" => 0,
            "msg" => ""
        ];
        if ($pohead_id <= 0)
        {
            $data["code"] = -1;
            $data["msg"] = "采购订单录入有误，请重新录入。";
        }
        else
        {
            $paymentrequests = Paymentrequest::where("created_at", ">", Carbon::now()->subMonth(3))
                ->where("pohead_id", $pohead_id)->where("amount", $amount)
                ->get();
            if ($paymentrequests->count() > 0)
            {
                $data["code"] = -1;
                $data["msg"] = "采购订单和付款金额在3个月内有重复申请。";
            }
        }
        return response()->json($data);
    }

    // 判断是否付款是否超额: 如果已付金额加上此次的提交金额大于合同金额，给出提醒
    public function exceedingpay($pohead_id, $amount = 0)
    {
        $data = [
            "code" => 0,
            "msg" => ""
        ];
        if ($pohead_id <= 0)
        {
            $data["code"] = -1;
            $data["msg"] = "采购订单录入有误，请重新录入。";
        }
        else
        {
            $pohead = Purchaseorder_hxold_simple::where('id', $pohead_id)->firstOrFail();
            $vendordecution=DB::table('vendordeductions')
                ->leftJoin('vendordeductionitems', 'vendordeductions.id', '=', 'vendordeductionitems.vendordeduction_id')
                ->where('vendordeductions.status','>=',0)
                ->where('vendordeductions.pohead_id','=',$pohead_id)
                ->select(DB::raw('sum(vendordeductionitems.quantity * vendordeductionitems.unitprice) as decutionamount'))->first();

            $dec_amount = 0.0;
            if(isset($vendordecution))
                $dec_amount=$vendordecution->decutionamount;
//            Log::info($dec_amount);
            if ($pohead->amount_paid + $amount > $pohead->amount)
            {
                $data["code"] = -2;
                $data["msg"] = "该采购订单已付款" . $pohead->amount_paid . '元，加上该付款单的' . $amount . '元后，会超过合同金额' . $pohead->amount . '元。';
            }


            if ($pohead->amount_paid + $amount > $pohead->amount - $dec_amount)
            {
                $ed=$pohead->amount - $dec_amount - $pohead->amount_paid;
                $data["code"] = -2;
                $data["msg"] = "该采购订单合同金额为" . $pohead->amount . '元，已付款' . $pohead->amount_paid . '元，供应商扣款' . $dec_amount . '元。本次申请的' . $amount . '元超过了可用额度的' . $ed . '元。';
            }
        }
//        Log::info($data["msg"]);
        return response()->json($data);
    }

    // 通过 pdfjs 访问pdf文件
    public function pdfjsviewer($pdffile = '')
    {
//        dd(request());
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Method: GET');
        header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin');

//        header('Access-Control-Allow-Headers: Range');
//        header('Access-Control-Expose-Headers: Accept-Ranges, Content-Encoding, Content-Length, Content-Range');

//        return view('viewer');
//        return view('pdfjs/build/generic/web/viewer?file=compressed.tracemonkey-pldi-09.pdf');
//        return view('pdfjs/build/generic/web/viewer.html?file=compressed.tracemonkey-pldi-09.pdf');

        return redirect('http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html') ;
//        return redirect('/pdfjs/build/generic/web/viewer.html?file=/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf') ;

        return redirect('/pdfjs/build/generic/web/viewer.html?file=http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf') ;
//        view('/pdfjs/build/generic/web/viewer.html?file=compressed.tracemonkey-pldi-09.pdf');
    }
}
