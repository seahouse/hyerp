<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Models\System\Annualbonussheet;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, Log;

class AnnualbonussheetController extends Controller
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
        $inputs = $request->all();
        $annualbonussheets = $this->searchrequest($request)->paginate(15);

//        $salarysheets = Salarysheet::latest('created_at')->paginate(10);
        return view('system.annualbonussheets.index', compact('annualbonussheets', 'inputs'));
    }

    public function search(Request $request)
    {
        $key = $request->input('key');
        $inputs = $request->all();
        $salarysheets = $this->searchrequest($request)->paginate(15);

        return view('system.annualbonussheets.index', compact('annualbonussheets', 'key', 'inputs', 'purchaseorders', 'totalamount'));
    }

    public static function searchrequest($request)
    {

        $query = Annualbonussheet::latest('created_at');


        if ($request->has('salary_datestart') && $request->has('salary_dateend'))
        {
            $query->whereRaw('salary_date between \'' . $request->input('salary_datestart') . '\' and \'' . $request->input('salary_dateend') . '\'');
        }



        $items = $query->select('annualbonussheets.*');

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
        $annualbonussheet = Annualbonussheet::findOrFail($id);
        $config = DingTalkController::getconfig();
        return view('system.annualbonussheets.mshow', compact('annualbonussheet', 'config'));
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        //
        return view('system.annualbonussheets.import');
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
                        if (count($input) >= 17)
                        {
                            if (!empty($input['姓名']))
                            {
//                                dd($input['姓名']);
                                $annualbonussheet = Annualbonussheet::where('username', $input['姓名'])->where('salary_date', $request->input('salary_date'))->first();
                                if (!isset($annualbonussheet))
                                {
                                    $data = [];
                                    $data['salary_date'] = $request->input('salary_date');
                                    $data['username']               = $input['姓名'];
                                    $user = User::where('name', $input['姓名'])->first();
                                    if (isset($user))
                                        $data['user_id']            = $user->id;
                                    $data['department']            = $input['部门'];
                                    $data['salaryincrease']       = isset($input['增长工资']) ? $input['增长工资'] : 0.0;
                                    $data['months']            = isset($input['月份']) ? $input['月份'] : 0.0;
                                    $data['yearend_salary']        = isset($input['年终工资']) ? $input['年终工资'] : 0.0;
                                    $data['yearend_bonus'] = isset($input['年终奖金']) ? $input['年终奖金'] : 0.0;
                                    $data['duty_subsidy']             = isset($input['职务补贴']) ? $input['职务补贴'] : 0.0;
                                    $data['duty_allowance']       = isset($input['职称津贴']) ? $input['职称津贴'] : 0.0;
                                    $data['forum_amount'] = isset($input['座谈会']) ? $input['座谈会'] : 0.0;
                                    $data['other_amount']            = isset($input['其他']) ? $input['其他'] : 0.0;
                                    $data['boss_prize']             = isset($input['老板奖']) ? $input['老板奖'] : 0.0;
                                    $data['amount']       = isset($input['发放金额']) ? $input['发放金额'] : 0.0;
                                    $data['goodemployee_amount']     = isset($input['优秀员工']) ? $input['优秀员工'] : 0.0;
                                    $data['totalamount']           = isset($input['合计']) ? $input['合计'] : 0.0;
                                    $data['individualincometax_amount'] = isset($input['个税']) ? $input['个税'] : 0.0;
                                    $data['actual_amount'] = isset($input['实际发放']) ? $input['实际发放'] : 0.0;
                                    $data['remark']                    = isset($input['备注']) ? $input['备注'] : '';
//                                    dd($data);
                                    $annualbonussheet = Annualbonussheet::create($data);
                                }
                                else
                                {
                                    $user = User::where('name', $input['姓名'])->first();
                                    if (isset($user))
                                        $annualbonussheet->user_id               = $user->id;
                                    $annualbonussheet->department                = $input['部门'];
                                    $annualbonussheet->salaryincrease           = isset($input['增长工资']) ? $input['增长工资'] : 0.0;
                                    $annualbonussheet->months               = isset($input['月份']) ? $input['月份'] : 0.0;
                                    $annualbonussheet->yearend_salary            = isset($input['年终工资']) ? $input['年终工资'] : 0.0;
                                    $annualbonussheet->yearend_bonus = isset($input['年终奖金']) ? $input['年终奖金'] : 0.0;
                                    $annualbonussheet->duty_subsidy                = isset($input['职务补贴']) ? $input['职务补贴'] : 0.0;
                                    $annualbonussheet->duty_allowance        = isset($input['职称津贴']) ? $input['职称津贴'] : 0.0;
                                    $annualbonussheet->forum_amount  = isset($input['座谈会']) ? $input['座谈会'] : 0.0;
                                    $annualbonussheet->other_amount               = isset($input['其他']) ? $input['其他'] : 0.0;
                                    $annualbonussheet->boss_prize                 = isset($input['老板奖']) ? $input['老板奖'] : 0.0;
                                    $annualbonussheet->amount           = isset($input['发放金额']) ? $input['发放金额'] : 0.0;
                                    $annualbonussheet->goodemployee_amount         = isset($input['优秀员工']) ? $input['优秀员工'] : 0.0;
                                    $annualbonussheet->totalamount              = isset($input['合计']) ? $input['合计'] : 0.0;
                                    $annualbonussheet->individualincometax_amount = isset($input['个税']) ? $input['个税'] : 0.0;
                                    $annualbonussheet->actual_amount = isset($input['实际发放']) ? $input['实际发放'] : 0.0;
                                    $annualbonussheet->remark                    = isset($input['备注']) ? $input['备注'] : '';
                                    $annualbonussheet->save();
                                }
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

        return redirect('system/annualbonussheet');
    }

    public function sendannualbonussheet(Request $request)
    {
        $annualbonussheets = $this->searchrequest($request)->get();
        foreach ($annualbonussheets as $annualbonussheet)
        {
            if ($annualbonussheet->user_id > 0)
            {
                Log::info($annualbonussheet->username);
//                DingTalkController::send_link($annualbonussheet->user->dtuserid, '', );

                $data = [
                    [
                        'key' => '姓名:',
                        'value' => $annualbonussheet->username,
                    ],
                    [
                        'key' => '增长工资:',
                        'value' => $annualbonussheet->salaryincrease,
                    ],
                    [
                        'key' => '合计:',
                        'value' => $annualbonussheet->totalamount,
                    ],
                    [
                        'key' => '实际发放:',
                        'value' => $annualbonussheet->actual_amount,
                    ],
                ];

                $msgcontent_data = [
                    'message_url' => url('mddauth/approval/system-annualbonussheet-' . $annualbonussheet->id . '-mshow'),
                    'pc_message_url' => '',
                    'head' => [
                        'bgcolor' => 'FFBBBBBB',
                        'text' => '您的奖金条等待签收'
                    ],
                    'body' => [
                        'title' => '您的奖金条等待签收，点击查看明细（仅手机端）。',
                        'form' => $data
                    ]
                ];
                $msgcontent = json_encode($msgcontent_data);

                $c = new DingTalkClient;
                $req = new CorpMessageCorpconversationAsyncsendRequest;

                $access_token = DingTalkController::getAccessToken();
                $req->setAgentId(config('custom.dingtalk.agentidlist.erpmessage'));
                $req->setUseridList($annualbonussheet->user->dtuserid);

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

        return '发送奖金条完成。';
    }
}
