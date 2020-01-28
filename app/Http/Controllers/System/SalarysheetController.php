<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Models\System\Salarysheet;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, Log;

class SalarysheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
//        $key = $request->input('key', '');
        $inputs = $request->all();
        $salarysheets = $this->searchrequest($request)->paginate(15);

//        $salarysheets = Salarysheet::latest('created_at')->paginate(10);
        return view('system.salarysheets.index', compact('salarysheets', 'inputs'));
    }

    public function search(Request $request)
    {
        $key = $request->input('key');
        $inputs = $request->all();
        $salarysheets = $this->searchrequest($request)->paginate(15);

        return view('system.salarysheets.index', compact('salarysheets', 'key', 'inputs', 'purchaseorders', 'totalamount'));
    }

    public static function searchrequest($request)
    {

        $query = Salarysheet::latest('created_at');


        if ($request->has('salary_datestart') && $request->has('salary_dateend'))
        {
            $query->whereRaw('salary_date between \'' . $request->input('salary_datestart') . '\' and \'' . $request->input('salary_dateend') . '\'');
        }



        $items = $query->select('salarysheets.*');

        return $items;
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

    public function mshow($id)
    {
        //
        $salarysheet = Salarysheet::findOrFail($id);
        $config = DingTalkController::getconfig();
        return view('system.salarysheets.mshow', compact('salarysheet', 'config'));
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
        Salarysheet::destroy($id);
        return redirect('system/salarysheet');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        //
        return view('system.salarysheets.import');
    }

    public function importstore(Request $request)
    {
        //
        $this->validate($request, [
            'salary_date'       => 'required',
//            'itemtype'                    => 'required',
//            'expirationdate'             => 'required',
//            'sohead_id'                   => 'required|integer|min:1',
//            'items_string'               => 'required',
//            'detailuse'               => 'required',
        ]);

        $file = $request->file('file');
//        dd($file->getRealPath());
//        $file = array_get($input,'file');
//        dd($file->public_path());
//        Log::info($request->getSession().getServletContext()->getReadPath("/xx"));

//        Excel::load(iconv('UTF-8', 'GBK', public_path("aaa.xls")) , function ($reader) {
//            dd($reader->get());
//            $reader->each(function ($sheet) {
//                $sheet->each(function ($row) {
//                    dd($row);
//                });
//            });
//        });

        // !! set config/excel.php
        // 'force_sheets_collection' => true,   // !!
        Excel::load($file->getRealPath(), function ($reader) use ($request) {
            $reader->each(function ($sheet) use (&$reader, $request) {
                Log::info('sheet: ' . $sheet->getTitle());
                $rowindex = 2;
                $shipment = null;
                $sheet->each(function ($row) use (&$rowindex, &$shipment, &$reader, $request) {
//                    Log::info('importstore 1: ');
//                    if ($rowindex > 3)
                    {
//                        dd($row->all());
//                        $input = array_values($row->toArray());
                        $input = $row->all();
//                        dd($input);
                        if (count($input) >= 24)
                        {
                            if (!empty($input['姓名']))
                            {
//                                dd($input['姓名']);
                                $salarysheet = Salarysheet::where('username', $input['姓名'])->where('salary_date', $request->input('salary_date'))->first();
                                if (!isset($salarysheet))
                                {
                                    $data = [];
                                    $data['salary_date'] = $request->input('salary_date');
                                    $data['username']               = $input['姓名'];
                                    $user = User::where('name', $input['姓名'])->first();
                                    if (isset($user))
                                        $data['user_id']            = $user->id;
                                    $data['department']            = $input['部门'];
                                    $data['attendance_days']       = isset($input['出勤天数']) ? $input['出勤天数'] : 0.0;
                                    $data['basicsalary']            = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
                                    $data['overtime_hours']        = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
                                    $data['absenteeismreduce_hours'] = isset($input['缺勤减扣小时']) ? $input['缺勤减扣小时'] : 0.0;
                                    $data['paid_hours']             = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
                                    $data['overtime_amount']       = isset($input['加班费']) ? $input['加班费'] : 0.0;
                                    $data['fullfrequently_award'] = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
                                    $data['meal_amount']            = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
                                    $data['car_amount']             = isset($input['车贴']) ? $input['车贴'] : 0.0;
                                    $data['business_amount']       = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
                                    $data['additional_amount']     = isset($input['补资']) ? $input['补资'] : 0.0;
                                    $data['house_amount']           = isset($input['房贴']) ? $input['房贴'] : 0.0;
                                    $data['hightemperature_amount'] = isset($input['高温费']) ? $input['高温费'] : 0.0;
                                    $data['absenteeismreduce_amount'] = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
                                    $data['shouldpay_amount']       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
                                    $data['borrowreduce_amount']   = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
                                    $data['personalsocial_amount'] = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
                                    $data['personalaccumulationfund_amount'] = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
                                    $data['individualincometax_amount'] = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
                                    $data['actualsalary_amount']    = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
                                    $data['remark']                    = isset($input['备注']) ? $input['备注'] : '';
//                                    dd($data);
                                    $salarysheet = Salarysheet::create($data);
                                }
                                else
                                {
                                    $user = User::where('name', $input['姓名'])->first();
                                    if (isset($user))
                                        $salarysheet->user_id               = $user->id;
                                    $salarysheet->department                = $input['部门'];
                                    $salarysheet->attendance_days           = isset($input['出勤天数']) ? $input['出勤天数'] : 0.0;
                                    $salarysheet->basicsalary               = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
                                    $salarysheet->overtime_hours            = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
                                    $salarysheet->absenteeismreduce_hours = isset($input['缺勤减扣小时']) ? $input['缺勤减扣小时'] : 0.0;
                                    $salarysheet->paid_hours                = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
                                    $salarysheet->overtime_amount        = isset($input['加班费']) ? $input['加班费'] : 0.0;
                                    $salarysheet->fullfrequently_award  = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
                                    $salarysheet->meal_amount               = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
                                    $salarysheet->car_amount                 = isset($input['车贴']) ? $input['车贴'] : 0.0;
                                    $salarysheet->business_amount           = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
                                    $salarysheet->additional_amount         = isset($input['补资']) ? $input['补资'] : 0.0;
                                    $salarysheet->house_amount              = isset($input['房贴']) ? $input['房贴'] : 0.0;
                                    $salarysheet->hightemperature_amount = isset($input['高温费']) ? $input['高温费'] : 0.0;
                                    $salarysheet->absenteeismreduce_amount = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
                                    $salarysheet->shouldpay_amount       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
                                    $salarysheet->borrowreduce_amount       = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
                                    $salarysheet->personalsocial_amount     = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
                                    $salarysheet->personalaccumulationfund_amount = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
                                    $salarysheet->individualincometax_amount = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
                                    $salarysheet->actualsalary_amount       = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
                                    $salarysheet->remark                    = isset($input['备注']) ? $input['备注'] : '';
                                    $salarysheet->save();
                                }
                            }
                            else
                            {
//                                if (empty($input[3]) && !empty($input[5]) && isset($shipment))
//                                {
//                                    $input['shipment_id'] = $shipment->id;
//                                    $input['contract_number'] = $input[5];
//                                    $input['purchaseorder_number'] = $input[7];
//                                    $input['qty_for_customer'] = $input[39];
//                                    $input['amount_for_customer'] = $input[40];
//                                    $input['volume'] = $input[53];
//                                    Shipmentitem::create($input);
//                                }
                            }
                        }
                    }
                    $rowindex++;
                });
            });

            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            Log::info('highestRow: ' . $highestRow);
            Log::info('highestColumn: ' . $highestColumn);

//            //  Loop through each row of the worksheet in turn
//            for ($row = 1; $row <= $highestRow; $row++)
//            {
//                //  Read a row of data into an array
//                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
//                    NULL, TRUE, FALSE);
//            }
        });

        return redirect('system/salarysheet');
    }

    public function sendsalarysheet(Request $request)
    {
        $salarysheets = $this->searchrequest($request)->get();
        foreach ($salarysheets as $salarysheet)
        {
            if ($salarysheet->user_id > 0)
            {
                Log::info($salarysheet->username);
//                DingTalkController::send_link($salarysheet->user->dtuserid, '', );

                $data = [
                    [
                        'key' => '姓名:',
                        'value' => $salarysheet->username,
                    ],
                    [
                        'key' => '基本工资:',
                        'value' => $salarysheet->basicsalary,
                    ],
                    [
                        'key' => '个人公积金:',
                        'value' => $salarysheet->personalaccumulationfund_amount,
                    ],
                    [
                        'key' => '实发工资:',
                        'value' => $salarysheet->actualsalary_amount,
                    ],
                ];

                $msgcontent_data = [
                    'message_url' => url('mddauth/approval/system-salarysheet-' . $salarysheet->id . '-mshow'),
                    'pc_message_url' => '',
                    'head' => [
                        'bgcolor' => 'FFBBBBBB',
                        'text' => '您的工资条等待签收'
                    ],
                    'body' => [
                        'title' => '您的工资条等待签收，点击查看明细（仅手机端）。',
                        'form' => $data
                    ]
                ];
                $msgcontent = json_encode($msgcontent_data);

                $c = new DingTalkClient;
                $req = new CorpMessageCorpconversationAsyncsendRequest;

                $access_token = DingTalkController::getAccessToken();
                $req->setAgentId(config('custom.dingtalk.agentidlist.erpmessage'));
                $req->setUseridList($salarysheet->user->dtuserid);

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
            }
        }

        return '发送工资条完成。';
    }
}
