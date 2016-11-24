<?php

namespace App\Http\Controllers\Approval;

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
use Auth, DB, Excel, PDF;
use Dompdf\Dompdf;
use Jenssegers\Agent\Agent;

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
        $request = request();
        if ($request->has('key'))
            $paymentrequests = $this->search2($request->input('key'));
        else
            $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);

        if ($request->has('key'))
        {
            $key = $request->input('key');
            return view('approval.paymentrequests.index', compact('paymentrequests', 'key'));
        }
        else
            return view('approval.paymentrequests.index', compact('paymentrequests'));
    }
    

    public function search(Request $request)
    {
        // dd(substr($request->header('referer'), strlen($request->header('origin'))));
        // dd($request->header('origin'));
        // dd($request->header('referer'));
        // dd($request->server('HTTP_REFERER'));
        $key = $request->input('key');
        if ($key == '')
            return redirect('/approval/paymentrequests');

        $paymentrequests = $this->search2($key);
        
        // $referer_url = substr($request->header('referer'), strlen($request->header('origin')));
        // dd($referer_url);
        return view('approval.paymentrequests.index', compact('paymentrequests', 'key'));
        // return view($referer_url, compact('paymentrequests', 'key'));
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
    public static function my($key = '')
    {
        $userid = Auth::user()->id;

        if ('' == $key)
            return Paymentrequest::latest('created_at')
                ->where('applicant_id', $userid)->paginate(10);

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
                ->where('approversetting_id', 0)->paginate(10);

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
        // dd("bbb");


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

                // DingTalkController::send_link($touser->dtuserid, '', 
                //     url('approval/paymentrequestapprovals/' . $input['paymentrequest_id'] . '/mcreate'), '',
                //     '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', 
                //     config('custom.dingtalk.agentidlist.approval'));    

                DingTalkController::send_link($touser->dtuserid, '', 
                    url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
                    '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', 
                    config('custom.dingtalk.agentidlist.approval'));
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
        return view('approval.paymentrequests.show', compact('paymentrequest', 'agent'));
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
        return view('approval.paymentrequests.mshow', compact('paymentrequest', 'agent'));
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
                // dd($paymentrequests["data"]);
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
        // $str .= '<p style="font-family: DroidSansFallback;">订单付款方式: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->paymethod) ? $paymentrequest->purchaseorder_hxold->sohead->paymethod : '') . '</p>';
        // $str .= '<p style="font-family: DroidSansFallback;">订单付款备注: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->paymethod_descrip) ? $paymentrequest->purchaseorder_hxold->sohead->paymethod_descrip : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">安装完毕日期: ' . (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate) ? $paymentrequest->purchaseorder_hxold->sohead->installeddate : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">应付款设备名称: ' . $paymentrequest->equipmentname . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">说明: ' . $paymentrequest->descrip . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">本次请款额: ' . $paymentrequest->amount . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款方式: ' . $paymentrequest->paymentmethod . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">开户行: ' . (isset($paymentrequest->vendbank_hxold->bankname) ? $paymentrequest->vendbank_hxold->bankname : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">银行账号: ' . (isset($paymentrequest->vendbank_hxold->accountnum) ? $paymentrequest->vendbank_hxold->accountnum : '') . '</p>';
        $str .= '<p style="font-family: DroidSansFallback;">付款日期: ' . $paymentrequest->datepay . '</p>';
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
}
