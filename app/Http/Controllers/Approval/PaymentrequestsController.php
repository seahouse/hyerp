<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Paymentrequest;
use Auth;

class PaymentrequestsController extends Controller
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
        // dd($input);
        // dd($request->input('amount', '0.0'));

        if ($input['amount'] == '')
            $input['amount'] = '0.0';

        $input['applicant_id'] = Auth::user()->id;

        // // generation number
        // $cPre = $input['numberpre'];
        // $lastReimbursement = Reimbursement::where('number', 'like', $cPre.date('Ymd').'%')->orderBy('id', 'desc')->first();
        // if ($lastReimbursement)
        // {
        //     $lastNumber = $lastReimbursement->number;
        //     $suffix = (string)((int)substr($lastNumber, -2) + 1);
        //     $suffix = str_pad($suffix, 2, '0', STR_PAD_LEFT);
        //     // dd($suffix);
        //     $number = substr($lastNumber, 0, strlen($lastNumber) - 2) . $suffix;
        // }
        // else
        //     $number = $cPre . date('Ymd') . '01';
        // $input['number'] = $number;        

        

        // // set approversetting_id 
        // $approversettingFirst = Approversetting::where('approvaltype_id', $this::$approvaltype_id)->orderBy('level')->first();
        // if ($approversettingFirst)
        //     $input['approversetting_id'] = $approversettingFirst->id;
        // else
        //     $input['approversetting_id'] = -1;

        $reimbursement = Paymentrequest::create($input);

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

        // // create reimbursement images
        // if ($reimbursement)
        // {
        //     $images = array_where($input, function($key, $value) {
        //         if (substr_compare($key, 'image_', 0, 6) == 0)
        //             return $value;
        //     });

        //     foreach ($images as $key => $value) {
        //         # code...
        //         // save image file.
        //         $sExtension = substr($value, strrpos($value, '.') + 1);
        //         // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
        //         // Storage::disk('local')->put($sFilename, file_get_contents($value));
        //         // Storage::move($sFilename, '../abcd.jpg');
        //         $dir = 'images/approval/reimbursement/' . $reimbursement->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
        //         $parts = explode('/', $dir);
        //         $filename = array_pop($parts);
        //         $dir = '';
        //         foreach ($parts as $part) {
        //             # code...
        //             $dir .= "$part/";
        //             if (!is_dir($dir)) {
        //                 mkdir($dir);
        //             }
        //         }                

        //         file_put_contents("$dir/$filename", file_get_contents($value));
        //         // file_put_contents('abcd.jpg', file_get_contents($value));

        //         // response()->download($value);
        //         // Storage::put('abcde.jpg', file_get_contents($value));
        //         // copy(storage_path('app') . '/' . $sFilename, '/images/' . $sFilename);

        //         // add image record
        //         $reimbursementimages = new Reimbursementimages;
        //         $reimbursementimages->reimbursement_id = $reimbursement->id;
        //         $reimbursementimages->path = "/$dir/$filename";     // add a '/' in the head.
        //         $reimbursementimages->save();
        //     }
        // }

        // if ($reimbursement)
        // {
        //     // send dingtalk message.
        //     $touser = $reimbursement->nextapprover();
        //     if ($touser)
        //     {
        //         DingTalkController::send($touser->dtuserid, '', 
        //             '来自' . $reimbursement->applicant->name . '的报销单需要您审批.', 
        //             config('custom.dingtalk.agentidlist.approval'));
        //         // $url = 'https://oapi.dingtalk.com/message/send';
        //         // $access_token = DingTalkController::getAccessToken();
        //         // $params = compact('access_token');
        //         // $data = [
        //         //     'touser' => $touser->dtuserid,
        //         //     'toparty' => '',
        //         //     'agentid' => '13231599',
        //         //     'msgtype' => 'text',
        //         //     'text' => [
        //         //         'content' => 'just a test.',
        //         //     ],
        //         // ];
        //         // DingTalkController::post($url, $params, json_encode($data));           
        //     }
      
        // }


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
