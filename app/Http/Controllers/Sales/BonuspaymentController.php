<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Bonuspayment_hxold;
use App\Models\Sales\Salesorder_hxold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, Log;

class BonuspaymentController extends Controller
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
    public function create($sohead_id)
    {
        //
        $sohead = Salesorder_hxold::findOrFail($sohead_id);
        return view('sales.bonuspayments.create', compact('sohead'));
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
        $bonuspayment = Bonuspayment_hxold::create($input);
        if (isset($bonuspayment))
            return '保存记录成功。';
        else
            return '保存记录失败。';
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

    public function import()
    {
        //
        return view('sales.bonuspayments.import');
    }

    public function importstore(Request $request)
    {
        //
        $this->validate($request, [
            'paymentdate'       => 'required',
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
        $record_count = 0;
        Excel::load($file->getRealPath(), function ($reader) use ($request, &$record_count) {
            $reader->each(function ($sheet) use (&$reader, $request, &$record_count) {
                Log::info('sheet: ' . $sheet->getTitle());
                $rowindex = 2;
                $shipment = null;
                $sheet->each(function ($row) use (&$rowindex, &$shipment, &$reader, $request, &$record_count) {
//                    Log::info('importstore 1: ');
//                    if ($rowindex > 3)
                    {
//                        dd($row->all());
//                        $input = array_values($row->toArray());
                        $input = $row->all();
//                        dd($input);
                        if (count($input) >= 9)
                        {
                            if (!empty($input['订单编号']))
                            {
//                                dd($input['订单编号']);
                                $sohead = Salesorder_hxold::where('number', $input['订单编号'])->first();
                                if (isset($sohead))
                                {
                                    $bonuspayment = null;
//                                    $bonuspayment = Bonuspayment_hxold::where('paymentdate', $request->input('paymentdate'))->where('sohead_id', $sohead->id)->first();
                                    if (!isset($bonuspayment))
                                    {
                                        $data = [];
                                        $data['sohead_id'] = $sohead->id;
                                        $data['paymentdate']             = $request->input('paymentdate');
                                        $data['bonusfactor']            = $sohead->getBonusfactorByPolicy();
                                        $data['amountpertenthousandbysohead']       = array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                                        $data['amount']                  = isset($input['应发奖金']) ? $input['应发奖金'] : 0.0;
//                                        $data['remark']                    = isset($input['备注']) ? $input['备注'] : '';
//                                    dd($data);
                                        $bonuspayment = Bonuspayment_hxold::create($data);
                                        if (isset($bonuspayment))  $record_count++;
                                    }
                                    else
                                    {
//                                        $user = User::where('name', $input['姓名'])->first();
//                                        if (isset($user))
//                                            $salarysheet->user_id               = $user->id;
//                                        $salarysheet->department                = $input['部门'];
//                                        $salarysheet->attendance_days           = isset($input['出勤天数']) ? $input['出勤天数'] : 0.0;
//                                        $salarysheet->basicsalary               = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
//                                        $salarysheet->overtime_hours            = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
//                                        $salarysheet->absenteeismreduce_hours = isset($input['缺勤减扣小时']) ? $input['缺勤减扣小时'] : 0.0;
//                                        $salarysheet->paid_hours                = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
//                                        $salarysheet->overtime_amount        = isset($input['加班费']) ? $input['加班费'] : 0.0;
//                                        $salarysheet->fullfrequently_award  = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
//                                        $salarysheet->meal_amount               = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
//                                        $salarysheet->car_amount                 = isset($input['车贴']) ? $input['车贴'] : 0.0;
//                                        $salarysheet->business_amount           = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
//                                        $salarysheet->additional_amount         = isset($input['补资']) ? $input['补资'] : 0.0;
//                                        $salarysheet->house_amount              = isset($input['房贴']) ? $input['房贴'] : 0.0;
//                                        $salarysheet->hightemperature_amount = isset($input['高温费']) ? $input['高温费'] : 0.0;
//                                        $salarysheet->absenteeismreduce_amount = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
//                                        $salarysheet->shouldpay_amount       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
//                                        $salarysheet->borrowreduce_amount       = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
//                                        $salarysheet->personalsocial_amount     = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
//                                        $salarysheet->personalaccumulationfund_amount = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
//                                        $salarysheet->individualincometax_amount = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
//                                        $salarysheet->actualsalary_amount       = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
//                                        $salarysheet->remark                    = isset($input['备注']) ? $input['备注'] : '';
//                                        $salarysheet->save();
                                    }
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

        dd('导入成功，共导入了' . $record_count . '条记录。');

        return redirect('system/salarysheet');
    }
}
