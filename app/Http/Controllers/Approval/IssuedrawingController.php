<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\HelperController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Issuedrawingattachment;
use App\Models\Approval\Issuedrawingcabinet;
use App\Models\Approval\Issuedrawingmodifyweightlog;
use App\Models\Approval\Issuedrawingtonnagedetail;
use App\Models\Basic\Company_hxold;
use App\Models\Basic\Manufacturingcenter_hxold;
use App\Models\System\Operationlog;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use Auth, Log, Agent;
use Validator, Storage;

class IssuedrawingController extends Controller
{
    private static $approvaltype_name = "下发图纸";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
        $key = $request->input('key', '');
        $approvalstatus = $request->input('approvalstatus', '');
        $paymentstatus = $request->input('paymentstatus');
        $inputs = $request->all();
        if (null !== request('key'))
            $issuedrawings = $this->searchrequest($request);
        else
            $issuedrawings = Issuedrawing::latest('created_at')->paginate(10);
        //        $purchaseorders = Purchaseorder_hxold::whereIn('id', $issuedrawings->pluck('pohead_id'))->get();
        //        $totalamount = Issuedrawing::sum('amount');

        //        return view('approval.paymentrequests.index');

        // if ($request->has('key'))
        // use request('key') for null compare, not $request->has('key')

        if (null !== request('key')) {
            return view('approval.issuedrawings.index', compact('issuedrawings', 'key', 'inputs', 'purchaseorders', 'totalamount'));
        } else {
            return view('approval.issuedrawings.index', compact('issuedrawings', 'purchaseorders', 'totalamount'));
        }
    }

    public function getitemsbysoheadid($sohead_id)
    {
        //
        $issuedrawings = Issuedrawing::where('sohead_id', $sohead_id)
            ->paginate(50);
        return $issuedrawings;
    }

    public function search(Request $request)
    {
        $key = $request->input('key');
        $approvalstatus = $request->input('approvalstatus');
        $paymentstatus = $request->input('paymentstatus');
        $inputs = $request->all();
        $issuedrawings = $this->searchrequest($request);
        //        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        //        $totalamount = Paymentrequest::sum('amount');

        return view('approval.issuedrawings.index', compact('issuedrawings', 'key', 'inputs', 'purchaseorders', 'totalamount'));
    }

    public static function searchrequest($request)
    {
        $key = $request->input('key');
        $approvalstatus = $request->input('status');

        $supplier_ids = [];
        $purchaseorder_ids = [];
        //        if (strlen($key) > 0)
        //        {
        //            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
        //            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');
        //        }

        $query = Issuedrawing::latest('created_at');

        if (strlen($key) > 0) {
            //            $query->where('business_id', 'like', '%'.$key.'%');
            $query->leftJoin('hxcrm2016.dbo.vorder', 'vorder.id', '=', 'issuedrawings.sohead_id');
            $query->where(function ($query) use ($key) {
                $query->where('business_id', 'like', '%' . $key . '%')
                    ->orWhere('hxcrm2016.dbo.vorder.number', 'like', '%' . $key . '%');
            });
        }

        if ($request->has('status'))
            $query->where('status', $request->input('status'));

        if ($request->has('sohead_id') && $request->input('sohead_id') > 0)
            $query->where('sohead_id', $request->input('sohead_id'));

        //        // paymentmethod
        //        if ($request->has('paymentmethod'))
        //        {
        //            $query->where('paymentmethod', $request->input('paymentmethod'));
        //        }
        //
        //        // payment status
        //        // because need search hxold database, so select this condition last.
        //        if ($request->has('paymentstatus'))
        //        {
        //            $paymentstatus = $request->input('paymentstatus');
        //            if ($paymentstatus == 0)
        //            {
        //                $query->where('approversetting_id', '0');
        //
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
        //
        //                // dd($paymentrequestids);
        //                $query->whereIn('id', $paymentrequestids);
        //            }
        //            elseif ($paymentstatus == -1)
        //            {
        //                $query->where('approversetting_id', '0');
        //
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
        //
        //                $query->whereIn('id', $paymentrequestids);
        //            }
        //        }


        $issuedrawings = $query->select('issuedrawings.*')
            ->paginate(10);

        // $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        // dd($purchaseorders->pluck('id'));

        return $issuedrawings;
    }

    public static function my(Request $request)
    {
        $userid = Auth::user()->id;

        $key = $request->input('key');

        $query = Issuedrawing::latest('created_at');
        $query->where('applicant_id', $userid);
        $query->where('status', '>', 0);

        if (strlen($key) > 0) {
            $query->where('business_id', 'like', '%' . $key . '%');
        }

        //        if (strlen($paymenttype) > 0)
        //        {
        //            $query->where('paymenttype', $paymenttype);
        //        }
        //
        //        if (strlen($projectname) > 0)
        //        {
        //            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
        //                ->where('descrip', 'like', '%'.$projectname .'%')
        //                ->pluck('id');
        //            $query->whereIn('pohead_id', $purchaseorder_ids);
        //        }
        //
        //        if (strlen($productname) > 0)
        //        {
        //            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
        //                ->where('productname', 'like', '%'.$productname .'%')
        //                ->pluck('id');
        //            $query->whereIn('pohead_id', $purchaseorder_ids);
        //        }
        //
        //        if (strlen($suppliername) > 0)
        //        {
        //            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$suppliername.'%')->pluck('id');
        //            $query->whereIn('supplier_id', $supplier_ids);
        //        }

        $issuedrawings = $query->select()->paginate(10);
        return $issuedrawings;
    }

    public static function myed(Request $request)
    {
        $userid = Auth::user()->id;

        $key = $request->input('key');

        $query = Issuedrawing::latest('created_at');
        $query->where('applicant_id', $userid);
        $query->where('status', '<=', 0);

        if (strlen($key) > 0) {
            $query->where('business_id', 'like', '%' . $key . '%');
        }

        //        if (strlen($paymenttype) > 0)
        //        {
        //            $query->where('paymenttype', $paymenttype);
        //        }
        //
        //        if (strlen($projectname) > 0)
        //        {
        //            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
        //                ->where('descrip', 'like', '%'.$projectname .'%')
        //                ->pluck('id');
        //            $query->whereIn('pohead_id', $purchaseorder_ids);
        //        }
        //
        //        if (strlen($productname) > 0)
        //        {
        //            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')
        //                ->where('productname', 'like', '%'.$productname .'%')
        //                ->pluck('id');
        //            $query->whereIn('pohead_id', $purchaseorder_ids);
        //        }
        //
        //        if (strlen($suppliername) > 0)
        //        {
        //            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$suppliername.'%')->pluck('id');
        //            $query->whereIn('supplier_id', $supplier_ids);
        //        }

        $issuedrawings = $query->select()->paginate(10);
        return $issuedrawings;
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
        //        $agent = new Agent();
        //
        //        return view('approval/paymentrequests/mcreate', compact('config', 'agent'));
        return view('approval/issuedrawings/mcreate', compact('config'));
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
        //
        $input = $request->all();
//        dd($input);

        $this->validate($request, [
            'designdepartment'      => 'required',
            //            'company_id'             => 'required|integer|min:1',
            'productioncompany'      => 'required',
            'materialsupplier'      => 'required',
            'sohead_id'             => 'required|integer|min:1',
            'overview'              => 'required',
            //            'tonnage'               => 'required|numeric',
            'drawingchecker_id'     => 'required|integer|min:1',
            'requestdeliverydate'   => 'required',
            'drawingcount'          => 'required|integer|min:1',
            'drawingattachments.*'  => 'required|file',
            //            'images.*'                => 'required|file',
            'images.*'                => 'required|image',
            //            'images.*'                => 'required|image|mimetypes:application/octet-stream',
            //            'images.*'                => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            //            'image_file'            => 'required|image',
            //            'image_file'            => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        //        $input = HelperController::skipEmptyValue($input);


        // dd($request->file('paymentnodeattachments')->getClientOriginalExtension());
        // dd($request->input('amount', '0.0'));

        // $input['company_name'] = '';
        // $company = Company_hxold::find($input['company_id']);
        // if (isset($company))
        //     $input['company_name'] = $company->name;

        $input['applicant_id'] = Auth::user()->id;

        // set approversetting_id
        $approvaltype_id = self::typeid();
        if ($approvaltype_id > 0) {
            $approversettingFirst = Approversetting::where('approvaltype_id', $approvaltype_id)->orderBy('level')->first();
            if ($approversettingFirst)
                $input['approversetting_id'] = $approversettingFirst->id;
            else
                $input['approversetting_id'] = -1;
        } else
            $input['approversetting_id'] = -1;

        $input['tonnage'] = 0.0;
        $issuedrawing = Issuedrawing::create($input);

        // create drawingattachments
        $drawingattachments_url = [];
        $drawingattachments_url2 = [];
        if ($issuedrawing) {
            $files = array_get($input, 'drawingattachments');
            $destinationPath = 'uploads/approval/issuedrawing/' . $issuedrawing->id . '/drawingattachments/';
            foreach ($files as $key => $file) {
                if ($file) {
                    $originalName = $file->getClientOriginalName();         // aa.xlsx
                    $extension = $file->getClientOriginalExtension();       // .xlsx
                    //                    Log::info('extension: ' . $extension);
                    $filename = date('YmdHis') . rand(100, 200) . '.' . $extension;
                    Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $issuedrawingattachment = new Issuedrawingattachment;
                    $issuedrawingattachment->issuedrawing_id = $issuedrawing->id;
                    $issuedrawingattachment->type = "drawingattachment";
                    $issuedrawingattachment->filename = $originalName;
                    $issuedrawingattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                    $issuedrawingattachment->save();

                    array_push($drawingattachments_url, url($destinationPath . $filename));
                    if (strcasecmp($extension, "pdf") == 0)
                        array_push($drawingattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                    else {
                        $filename2 = str_replace(".", "_", $filename);
                        array_push($drawingattachments_url2, url("$destinationPath$filename2"));
                    }
                    //                    array_push($drawingattachments_url2, url('mddauth/pdfjs-viewer') . "?file=" . "/$destinationPath$filename");


                    //                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
                    //                        url('mddauth/approval/approval-paymentrequestapprovals-' . $paymentrequest->id . '-mcreate'), '',
                    //                        '供应商付款审批', '来自' . $paymentrequest->applicant->name . '的付款申请单需要您审批.', $paymentrequest,
                    //                        config('custom.dingtalk.agentidlist.approval'));
                }
            }
        }

        // create issuedrawingcabinets
        if (isset($issuedrawing)) {
            $issuedrawingcabinet_items = json_decode($input['items_string']);
            foreach ($issuedrawingcabinet_items as $issuedrawingcabinet_item) {
                if (strlen($issuedrawingcabinet_item->name) > 0) {
                    $item_array = json_decode(json_encode($issuedrawingcabinet_item), true);
                    $item_array['issuedrawing_id'] = $issuedrawing->id;
                    $issuedrawingcabinet = Issuedrawingcabinet::create($item_array);
                }
            }
        }

        // create issuedrawingtonnagedetails
        $totalprice = 0.0;
        $totaltonnage = 0.0;
        $dtunitpricedetail = [];
        $paintthickness_1 = 0.0;        // 薄板漆膜厚度
        $paintthickness_2 = 0.0;        // 焊接漆膜厚度
        $paintthickness_3 = 0.0;        // 不锈钢漆膜厚度
        $paintthickness_4 = 0.0;        // 栓接漆膜厚度
        if (isset($issuedrawing)) {
            $tonnagedetailArray = json_decode($request->input('tonnagedetails_string'), true);
            foreach ($tonnagedetailArray as $tonnagedetaildata) {
                $tonnagedetaildata['issuedrawing_id'] = $issuedrawing->id;
                if (strlen($tonnagedetaildata['name']) > 0) {
                    if ($tonnagedetaildata['tonnage'] > 0.0 || $tonnagedetaildata['tonnage'] < 0.0)
                    {
                        $issuedrawingtonnagedetail = Issuedrawingtonnagedetail::create($tonnagedetaildata);

                        if (isset($issuedrawingtonnagedetail))
                        {
                            $price = $issuedrawingtonnagedetail->unitprice * $issuedrawingtonnagedetail->tonnage;
                            $totalprice += $price;
                            $totaltonnage += $issuedrawingtonnagedetail->tonnage;
                            array_push($dtunitpricedetail, $issuedrawingtonnagedetail->name . ':' . $issuedrawingtonnagedetail->tonnage . '吨*' . $issuedrawingtonnagedetail->unitprice . '元=' . $price . '元');

                            switch ($issuedrawingtonnagedetail->name)
                            {
                                case 'KS套筒':
                                case 'KS蜗壳':
                                case '风栅套筒':
                                case '灰斗箱体':
                                case '焊接钢架':
                                case '灰斗箱体':
                                case '平台爬梯':
                                case '吸收塔':
                                case '进风烟道':
                                case '上箱体':
                                case '尼鲁蜗壳':
                                case '希格斯蜗壳':
                                case 'GGH':
                                case 'SGH':
                                case 'VOC':
                                case '栓接钢架':
                                case 'SCR反应器':
                                    $paintthickness_1 += $issuedrawingtonnagedetail->tonnage;
                                    break;
                                case '滑动支架':
                                case '包装支架':
                                    $paintthickness_2 += $issuedrawingtonnagedetail->tonnage;
                                    break;
                                case '不锈钢罐':
                                    $paintthickness_3 += $issuedrawingtonnagedetail->tonnage;
                                    break;
//                                case '螺栓钢架':
//                                    $paintthickness_4 += $issuedrawingtonnagedetail->tonnage;
//                                    break;
                            }
                        }
                    }
                }
            }
            $input['tonnage'] = $totaltonnage;
            $input['tonnagedetails'] = implode("\n", $dtunitpricedetail);

            $paintthicknesstonnagedetail = [];
            if ($paintthickness_1 > 0.0) array_push($paintthicknesstonnagedetail, '薄板漆膜厚度' . $paintthickness_1 . '吨');
            if ($paintthickness_2 > 0.0) array_push($paintthicknesstonnagedetail, '焊接漆膜厚度' . $paintthickness_2 . '吨');
            if ($paintthickness_3 > 0.0) array_push($paintthicknesstonnagedetail, '不锈钢漆膜厚度' . $paintthickness_3 . '吨');
            if ($paintthickness_4 > 0.0) array_push($paintthicknesstonnagedetail, '栓接漆膜厚度' . $paintthickness_4 . '吨');
            $input['paintthicknesstonnagedetails'] = implode("\n", $paintthicknesstonnagedetail);
            $issuedrawing->update(['tonnage' => $totaltonnage]);
        }

        $image_urls = [];
        // create images in the desktop
        if ($issuedrawing) {
            $files = array_get($input, 'images');
            $destinationPath = 'uploads/approval/issuedrawing/' . $issuedrawing->id . '/images/';
            if ($files) {
                foreach ($files as $key => $file) {
                    if ($file) {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();       // .xlsx
                        $filename = date('YmdHis') . rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis') . rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $issuedrawingattachment = new Issuedrawingattachment;
                        $issuedrawingattachment->issuedrawing_id = $issuedrawing->id;
                        $issuedrawingattachment->type = "image";
                        $issuedrawingattachment->filename = $originalName;
                        $issuedrawingattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $issuedrawingattachment->save();

                        array_push($image_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // create images from dingtalk mobile
        if ($issuedrawing) {
            $images = array_where($input, function ($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/issuedrawing/' . $issuedrawing->id . '/images/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/issuedrawing/' . $issuedrawing->id . '/' . date('YmdHis') . rand(100, 200) . '.' . $sExtension;
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

                // response()->download($value);
                // Storage::put('abcde.jpg', file_get_contents($value));
                // copy(storage_path('app') . '/' . $sFilename, '/images/' . $sFilename);

                // add image record
                $issuedrawingattachment = new Issuedrawingattachment;
                $issuedrawingattachment->issuedrawing_id = $issuedrawing->id;
                $issuedrawingattachment->type = "image";     // add a '/' in the head.
                $issuedrawingattachment->path = "/$dir$filename";     // add a '/' in the head.
                $issuedrawingattachment->save();

                array_push($image_urls, url($destinationPath . $value));
            }
        }

        if (isset($issuedrawing)) {
            $input['drawingattachments_url'] = implode(" , ", $drawingattachments_url2);
            $input['associatedapprovals'] = strlen($input['associatedapprovals']) > 0 ? json_encode(explode(",", $input['associatedapprovals'])) : "";

            $input['image_urls'] = json_encode($image_urls);
            $input['approvers'] = $issuedrawing->approvers();
            Log::info($input['approvers']);
            if ($input['approvers'] == "")
                $input['approvers'] = config('custom.dingtalk.default_approvers');       // wuceshi for test
            //            $input['cabinet'] = $input['cabinetname'] . ":" . $input['cabinetquantity'];
            if ($request->has('bolt') && $request->get('bolt') == '1')
                $input['bolt_str'] = '是';
            else
                $input['bolt_str'] = '否';

            $input['manufacturingcenter'] = "";
            $manufacturingcenter = Manufacturingcenter_hxold::find($input['manufacturingcenter_id']);
            if (isset($manufacturingcenter))
                $input['manufacturingcenter'] = $manufacturingcenter->name;

            $response = DingTalkController::issuedrawing($input);
            //            Log::info($response);
            $responsejson = json_decode($response);
//            dd($responsejson);
            if ($responsejson->errcode <> 0) {
                $issuedrawing->forceDelete();
                Log::info(json_encode($input));
                dd('钉钉端创建失败: ' . $responsejson->errmsg);
            } else {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->process_instance_id;

                if ($input['syncdtdesc'] == "许昌")
                    $response = DingTalkController::processinstance_get2($process_instance_id);
                else
                    $response = DingTalkController::processinstance_get($process_instance_id);
                $responsejson = json_decode($response);
                $business_id = '';
                if ($responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->ding_open_errcode == 0)
                    $business_id = $responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->process_instance->business_id;

                $issuedrawing->process_instance_id = $process_instance_id;
                $issuedrawing->business_id = $business_id;
                $issuedrawing->save();

                // send dingtalk message.
                $touser = $issuedrawing->nextapprover();
                if ($touser) {

                    //                    DingTalkController::send_link($touser->dtuserid, '',
                    //                        url('mddauth/approval/approval-paymentrequestapprovals-' . $issuedrawing->id . '-mcreate'), '',
                    //                        '供应商付款审批', '来自' . $issuedrawing->applicant->name . '的付款申请单需要您审批.',
                    //                        config('custom.dingtalk.agentidlist.approval'));
                    //
                    //                    if (Auth::user()->email == "admin@admin.com")
                    //                    {
                    //                        DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
                    //                            url('mddauth/approval/approval-paymentrequestapprovals-' . $issuedrawing->id . '-mcreate'), '',
                    //                            '供应商付款审批', '来自' . $issuedrawing->applicant->name . '的付款申请单需要您审批.', $issuedrawing,
                    //                            config('custom.dingtalk.agentidlist.approval'));
                    //                    }

                }
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
        $issuedrawing = Issuedrawing::findOrFail($id);  //dd($issuedrawing->drawingchecker);
        //        $agent = new Agent();
        $config = DingTalkController::getconfig();
        return view('approval.issuedrawings.show', compact('issuedrawing', 'config'));
    }

    public function mshow($id)
    {
        //
        $issuedrawing = Issuedrawing::findOrFail($id);
        $config = DingTalkController::getconfig();
        return view('approval.issuedrawings.mshow', compact('issuedrawing', 'config'));
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
        $issuedrawing = Issuedrawing::findOrFail($id);
        $issuedrawing->update($request->all());
        return redirect('approval/issuedrawing');
    }

    public function updateweight(Request $request, $id)
    {
        //
        $issuedrawing = Issuedrawing::findOrFail($id);
        $issuedrawing->update($request->all());

        $input = $request->all();
        $input['operator_id'] = Auth::user()->id;

        Issuedrawingmodifyweightlog::create($input);

        //        Operationlog::create(['table_name' => Operationlog::$ISSUEDRAWING,
        //            'table_id' => $issuedrawing->id,
        //            'operation'     => '重量由' . $request->input('tonnage_before') . '更新为' . $request->input('tonnage'),
        //            'operator_id'   => Auth::user()->id,
        //        ]);

        return redirect('approval/issuedrawing');
    }

    public function mupdateweight(Request $request, $id)
    {
        //
        $issuedrawing = Issuedrawing::findOrFail($id);
        $issuedrawing->update($request->all());

        $input = $request->all();
        $input['operator_id'] = Auth::user()->id;

        Issuedrawingmodifyweightlog::create($input);

        return 'success';
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
        if ($approvaltype) {
            return $approvaltype->id;
        }
        return 0;
    }

    public static function updateStatusByProcessInstanceId($processInstanceId, $status)
    {
        $issuedrawing = Issuedrawing::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($issuedrawing) {
            $issuedrawing->status = $status;
            $issuedrawing->save();
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $issuedrawing = Issuedrawing::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($issuedrawing) {
            $issuedrawing->forceDelete();
        }
    }

    public function modifyweight($id)
    {
        //
        $issuedrawing = Issuedrawing::findOrFail($id);
        return view('approval.issuedrawings.modifyweight', compact('issuedrawing'));
    }

    public function gettonnagedetailhtml(Request $request)
    {
        $strhtml = "";
        if ($request->has('selecttype') && $request->has('selectarea')) {
            foreach (config('custom.dingtalk.approversettings.issuedrawing.tonnagedetail.' . $request->input('selectarea') . '.' . $request->input('selecttype')) as $key => $value) {
                $strhtml .= "<div class=\"form-group\" name=\"div_unitpriceitem\">";
                $strhtml .= '<label for="paowan" class="col-xs-4 col-sm-2 control-label">' . $value . ':</label>
                            <div class="col-sm-5 col-xs-4">
                            <input class="form-control" placeholder="吨数" ="" name="tonnage" type="text" data-name="' . $value . '">
                            </div>
                            <div class="col-sm-5 col-xs-4">';
                //                $strhtml .='<input class="form-control" placeholder="单价" ="" name="unitprice" type="text" value="' . $value[$request->input('productioncompany')][$request->input('selectarea')] . '" readonly="readonly">';
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
