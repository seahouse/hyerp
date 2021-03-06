<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Models\System\Salarysheet;
use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

    public function mobileindex()
    {
        $username = Auth::user()->name;
        //        dd($username);
        $request = request();
        //        $key = $request->input('key', '');
        $inputs = $request->all();
        //        $username='陆增贵';
        //        $salarysheets = Salarysheet::where('username',$username)->first();
        //        dd($salarysheets);
        // 只列出一年（12个月）以内的工资单列表
        $months = 12;
        $salarysheets = Salarysheet::where('username', $username)->latest('salary_date')->take($months)->get();
        return view('system.salarysheets.mobileindex', compact('salarysheets', 'inputs', 'months'));
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

        if ($request->has('salary_datestart') && $request->has('salary_dateend')) {
            $salary_datestart = Carbon::parse($request->input('salary_datestart'))->toDateString();
            $salary_dateend = Carbon::parse($request->input('salary_dateend'))->addMonth()->addDay(-1)->toDateString();
            //            $query->whereRaw('salary_date between \'' . $salary_datestart . '\' and \'' . $salary_dateend . '\'');
            $query->whereBetween('salary_date', [$salary_datestart, $salary_dateend]);
        }

        if ($request->has('salary_date') && $request->has('salary_date')) {
            $salary_datestart = Carbon::parse($request->input('salary_date'))->toDateString();
            $salary_dateend = Carbon::parse($request->input('salary_date'))->addMonth()->addDay(-1)->toDateString();
            //            $query->whereRaw('salary_date between \'' . $salary_datestart . '\' and \'' . $salary_dateend . '\'');
            $query->whereBetween('salary_date', [$salary_datestart, $salary_dateend]);

            if ($request->has('send_batch') && $request->has('send_batch')) {
                $query->where('batch', $request->input('send_batch'));
            }
        }

        if ($request->has('salary_batch') && $request->has('salary_batch')) {
            $query->where('batch', $request->input('salary_batch'));
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
        $this->validate($request, [
            'salary_date'       => 'required',
        ]);

        $salary_date = Carbon::parse($request->input('salary_date'))->toDateString();
        // dd($salary_date);
        $file = $request->file('file');

        if (null != $file) {
            $batch = Salarysheet::where('salary_date', $salary_date)->max('batch');
            Log::info($batch);
            $batch = isset($batch) ? $batch + 1 : 1;
            Log::info($batch);
            // dd($batch);
            Excel::load($file->getRealPath(), function ($reader) use ($request, $salary_date, $batch) {
                $reader->each(function ($sheet) use (&$reader, $request, $salary_date, $batch) {
                    Log::info('sheet: ' . $sheet->getTitle());
                    $rowindex = 2;
                    $shipment = null;
                    $sheet->each(function ($row) use (&$rowindex, &$shipment, &$reader, $request, $salary_date, $batch) { {
                            $input = $row->all();
                            if (count($input) < 24 || empty($input['姓名'])) return;
                            // dd($input['姓名']);
                            // $salarysheet = Salarysheet::where('username', $input['姓名'])->where('salary_date', $salary_date)->first();
                            // if (!isset($salarysheet)) {
                            $data = [];
                            $data['salary_date'] = $salary_date;
                            $data['username']               = $input['姓名'];
                            $user = User::where('name', $input['姓名'])->first();
                            if (isset($user))
                                $data['user_id']            = $user->id;
                            $data['department']            = $input['部门'];
                            $data['workdays_target']       = isset($input['本月工作日']) ? $input['本月工作日'] : 0.0;
                            $data['attendance_days']       = isset($input['实际出勤天数']) ? $input['实际出勤天数'] : 0.0;
                            $data['basicsalary']            = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
                            $data['overtime_hours']        = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
                            $data['absenteeismreduce_hours'] = isset($input['缺勤减扣']) ? $input['缺勤减扣'] : 0.0;
                            $data['paid_hours']             = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
                            $data['overtime_amount']       = isset($input['加班费']) ? $input['加班费'] : 0.0;
                            $data['fullfrequently_award'] = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
                            $data['meal_amount']            = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
                            $data['car_amount']             = isset($input['车贴']) ? $input['车贴'] : 0.0;
                            $data['business_amount']       = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
                            $data['additional_amount']     = isset($input['补资']) ? $input['补资'] : 0.0;
                            $data['house_amount']           = isset($input['房贴']) ? $input['房贴'] : 0.0;
                            $data['hightemperature_amount'] = isset($input['高温费']) ? $input['高温费'] : 0.0;
                            $data['travel_amount']          = isset($input['差旅费']) ? $input['差旅费'] : 0.0;
                            $data['absenteeismreduce_amount'] = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
                            $data['shouldpay_amount']       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
                            $data['borrowreduce_amount']   = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
                            $data['personalsocial_amount'] = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
                            $data['personalaccumulationfund_amount'] = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
                            $data['individualincometax_amount'] = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
                            $data['actualsalary_amount']    = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
                            $data['remark']                    = isset($input['备注']) ? $input['备注'] : '';
                            $data['batch'] = $batch;
                            // dd($data);
                            $salarysheet = Salarysheet::create($data);
                            // } else {
                            // $user = User::where('name', $input['姓名'])->first();
                            // if (isset($user))
                            //     $salarysheet->user_id               = $user->id;
                            // $salarysheet->department                = $input['部门'];
                            // $salarysheet->workdays_target           = isset($input['本月工作日']) ? $input['本月工作日'] : 0.0;
                            // $salarysheet->attendance_days           = isset($input['实际出勤天数']) ? $input['实际出勤天数'] : 0.0;
                            // $salarysheet->basicsalary               = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
                            // $salarysheet->overtime_hours            = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
                            // $salarysheet->absenteeismreduce_hours = isset($input['缺勤减扣']) ? $input['缺勤减扣'] : 0.0;
                            // $salarysheet->paid_hours                = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
                            // $salarysheet->overtime_amount        = isset($input['加班费']) ? $input['加班费'] : 0.0;
                            // $salarysheet->fullfrequently_award  = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
                            // $salarysheet->meal_amount               = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
                            // $salarysheet->car_amount                 = isset($input['车贴']) ? $input['车贴'] : 0.0;
                            // $salarysheet->business_amount           = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
                            // $salarysheet->additional_amount         = isset($input['补资']) ? $input['补资'] : 0.0;
                            // $salarysheet->house_amount              = isset($input['房贴']) ? $input['房贴'] : 0.0;
                            // $salarysheet->hightemperature_amount = isset($input['高温费']) ? $input['高温费'] : 0.0;
                            // $salarysheet->travel_amount             = isset($input['差旅费']) ? $input['差旅费'] : 0.0;
                            // $salarysheet->absenteeismreduce_amount = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
                            // $salarysheet->shouldpay_amount       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
                            // $salarysheet->borrowreduce_amount       = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
                            // $salarysheet->personalsocial_amount     = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
                            // $salarysheet->personalaccumulationfund_amount = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
                            // $salarysheet->individualincometax_amount = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
                            // $salarysheet->actualsalary_amount       = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
                            // $salarysheet->remark                    = isset($input['备注']) ? $input['备注'] : '';
                            // $salarysheet->save();
                            // }
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
            });
        }

        return redirect('system/salarysheet');
    }

    public function sendsalarysheet(Request $request)
    {
        $salarysheets = $this->searchrequest($request)->get();
        foreach ($salarysheets as $salarysheet) {
            if ($salarysheet->user_id > 0) {
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
                        'title' => '您的工资条等待签收，点击查看明细。',
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
                if ($resp->code != "0") {
                    Log::info($resp->msg . ": " . $resp->sub_msg);
                }
            }
        }

        return '发送工资条完成。';
    }
}
