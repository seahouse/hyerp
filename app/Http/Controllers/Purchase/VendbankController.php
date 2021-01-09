<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Purchase\Vendbank_hxold;
use App\Http\Controllers\HelperController;
use Log;

class VendbankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);
        // return view('approval.paymentrequests.index', compact('paymentrequests'));
    }

	/**
     * Display a listing of the resource by search vendor id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getitemsbyvendid($vendid)
    {
        //
    	// dd(Vendbank_hxold::getDateFormat());
        $vendbanks = Vendbank_hxold::where('vendinfo_id', $vendid)->paginate(10);
        return $vendbanks;
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
        return view('approval/paymentrequests/mcreate', compact('config'));
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
        $input = $request->all();
        $input = HelperController::skipEmptyValue($input);
        // dd($input);



        $vendbank = Vendbank_hxold::create($input);

        // set isdefault field
        if ($vendbank)
        {
        	Vendbank_hxold::where('vendinfo_id', $vendbank->vendinfo_id)->where('id', '<>', $vendbank->id)
        		->update(['isdefault' => 0]);
        }


        return json_encode($vendbank);
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


        




        $paymentrequest = Paymentrequest::create($input);

        // // create reimbursement travels
        // if ($reimbursement)
        // {
        //     $travels = array_where($input, function($key, $value) {     
        //         if (substr_compare($key, 'travel_', 0, 7) == 0)
        //             return $value;
        //     });
        //     $travelList = [];
        //     foreach ($travels as $key => $value) {
        //         $hh = substr($key, 0, 9);
        //         $kk = substr($key, 9);
        //         if (!array_has($travelList, $hh))
        //             $travelList[$hh] = array($kk => $value);
        //         else
        //             $travelList[$hh] = array_add($travelList[$hh], $kk, $value);

        //     }

        //     $seq = 0;
        //     foreach ($travelList as $key => $value) {
        //         // add reimbursementtravels record
        //         $value['reimbursement_id'] = $reimbursement->id;
        //         $value['seq'] = ++$seq;
        //         Reimbursementtravel::create($value);
        //     }
        // }

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
                DingTalkController::send($touser->dtuserid, '', 
                    '来自' . $paymentrequest->applicant->name . '的付款单需要您审批.', 
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
        return view('approval.paymentrequests.show', compact('paymentrequest'));
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
        return view('approval.paymentrequests.mshow', compact('paymentrequest'));
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
}
