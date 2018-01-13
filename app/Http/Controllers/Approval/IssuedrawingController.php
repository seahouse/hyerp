<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\HelperController;
use App\Models\Approval\Approvaltype;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Issuedrawing;
use App\Models\Approval\Issuedrawingattachment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use Auth;
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
//        $imagefiles = $request->file('images') ;
////        $imagefiles = array_get($request->all(),'images');
////        dd($imagefiles);
//        foreach ($imagefiles as $imagefile)
//        {
//            $tempimage = array('image' =>  $imagefile);
////            dd($tempimage);
//            $rules = array(
//            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
////                'images' => 'image' // max 10000kb
//            );
//            $validator = Validator::make($tempimage, $rules);
//            dd($validator->errors());
//        }
//        $rules = array(
//            '*' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000' // max 10000kb
////            'images' => 'image' // max 10000kb
//        );
//        $validator = Validator::make($imagefiles, $rules);
//        dd($validator->errors());
//        $files = $request->input('images');
//        dd($files);
        $input = $request->all();
//        dd($input);
//        $request->file('image_file');
//        dd($input->file('image_file'));
//        dd($input);
        $this->validate($request, [
            'designdepartment'      => 'required',
            'productioncompany'      => 'required',
            'materialsupplier'      => 'required',
            'sohead_id'             => 'required|integer|min:1',
            'overview'              => 'required',
            'tonnage'               => 'required|numeric',
            'drawingchecker_id'     => 'required|integer|min:1',
            'requestdeliverydate'   => 'required',
            'drawingcount'          => 'required|integer|min:1',
            'drawingattachments.*'  => 'required|file',
            'images.*'                => 'required|image',
//            'images.*'                => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
//            'image_file'            => 'required|image',
//            'image_file'            => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        $input = HelperController::skipEmptyValue($input);
//        DingTalkController::issuedrawing($input);
//        dd($input);
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

        $issuedrawing = Issuedrawing::create($input);
        if (isset($issuedrawing))
        {
            $response = DingTalkController::issuedrawing($input);
            $responsejson = json_decode($response);
            if ($responsejson->dingtalk_smartwork_bpms_processinstance_create_response->result->ding_open_errcode <> 0)
            {
                $issuedrawing->forceDelete();
                dd('钉钉端创建失败: ' . $responsejson->dingtalk_smartwork_bpms_processinstance_create_response->result->error_msg);
            }
        }


        // create drawingattachments
        if ($issuedrawing)
        {
            $files = array_get($input,'drawingattachments');
            $destinationPath = 'uploads/approval/issuedrawing/' . $issuedrawing->id . '/drawingattachments/';
            foreach ($files as $key => $file) {
                if ($file)
                {
                    $originalName = $file->getClientOriginalName();
                    Storage::put($destinationPath . $originalName, file_get_contents($file->getRealPath()));

                    $extension = $file->getClientOriginalExtension();
                    $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                    // $fileName = rand(11111, 99999) . '.' . $extension;
                    $upload_success = $file->move($destinationPath, $filename);

                    // add database record
                    $issuedrawingattachment = new Issuedrawingattachment;
                    $issuedrawingattachment->issuedrawing_id = $issuedrawing->id;
                    $issuedrawingattachment->type = "drawingattachment";
                    $issuedrawingattachment->filename = $originalName;
                    $issuedrawingattachment->path = "/$destinationPath$originalName";     // add a '/' in the head.
                    $issuedrawingattachment->save();
                }

            }

        }


        // create images in the desktop
        if ($issuedrawing)
        {
            $files = array_get($input,'images');
            $destinationPath = 'uploads/approval/issuedrawing/' . $issuedrawing->id . '/images/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();
                        Storage::put($destinationPath . $originalName, file_get_contents($file->getRealPath()));

                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $issuedrawingattachment = new Issuedrawingattachment;
                        $issuedrawingattachment->issuedrawing_id = $issuedrawing->id;
                        $issuedrawingattachment->type = "image";
                        $issuedrawingattachment->filename = $originalName;
                        $issuedrawingattachment->path = "/$destinationPath$originalName";     // add a '/' in the head.
                        $issuedrawingattachment->save();
                    }

                }
            }
        }
        dd($issuedrawing);

        // create reimbursement images
        if ($issuedrawing)
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
                $dir = 'images/approval/paymentrequest/' . $issuedrawing->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
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
                $paymentrequestattachment->paymentrequest_id = $issuedrawing->id;
                $paymentrequestattachment->type = "image";     // add a '/' in the head.
                $paymentrequestattachment->path = "/$dir$filename";     // add a '/' in the head.
                $paymentrequestattachment->save();
            }
        }

        if ($issuedrawing)
        {
            // send dingtalk message.
            $touser = $issuedrawing->nextapprover();
            if ($touser)
            {
                // DingTalkController::send($touser->dtuserid, '',
                //     '来自' . $issuedrawing->applicant->name . '的付款单需要您审批.',
                //     config('custom.dingtalk.agentidlist.approval'));

                // DingTalkController::send_link($touser->dtuserid, '',
                //     url('approval/paymentrequestapprovals/' . $input['paymentrequest_id'] . '/mcreate'), '',
                //     '供应商付款审批', '来自' . $issuedrawing->applicant->name . '的付款申请单需要您审批.',
                //     config('custom.dingtalk.agentidlist.approval'));

                DingTalkController::send_link($touser->dtuserid, '',
                    url('mddauth/approval/approval-paymentrequestapprovals-' . $issuedrawing->id . '-mcreate'), '',
                    '供应商付款审批', '来自' . $issuedrawing->applicant->name . '的付款申请单需要您审批.',
                    config('custom.dingtalk.agentidlist.approval'));

                if (Auth::user()->email == "admin@admin.com")
                {
                    DingTalkController::send_oa_paymentrequest($touser->dtuserid, '',
                        url('mddauth/approval/approval-paymentrequestapprovals-' . $issuedrawing->id . '-mcreate'), '',
                        '供应商付款审批', '来自' . $issuedrawing->applicant->name . '的付款申请单需要您审批.', $issuedrawing,
                        config('custom.dingtalk.agentidlist.approval'));
                }

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
}
